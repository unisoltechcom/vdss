<?php

/** PHPExcel root directory */
if (!defined('PHPEXCEL_ROOT')) {
    /**
     * @ignore
     */
    define('PHPEXCEL_ROOT', dirname(__FILE__) . '/../');
    require(PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php');
}

if (!defined('CALCULATION_REGEXP_CELLREF')) {
    //    Test for support of \P (multibyte options) in PCRE
    if (defined('PREG_BAD_UTF8_ERROR')) {
        //    Cell reference (cell or range of cells, with or without a sheet reference)
        define('CALCULATION_REGEXP_CELLREF', '((([^\s,!&%^\/\*\+<>=-]*)|(\'[^\']*\')|(\"[^\"]*\"))!)?\$?([a-z]{1,3})\$?(\d{1,7})');
        //    Named Range of cells
        define('CALCULATION_REGEXP_NAMEDRANGE', '((([^\s,!&%^\/\*\+<>=-]*)|(\'[^\']*\')|(\"[^\"]*\"))!)?([_A-Z][_A-Z0-9\.]*)');
    } else {
        //    Cell reference (cell or range of cells, with or without a sheet reference)
        define('CALCULATION_REGEXP_CELLREF', '(((\w*)|(\'[^\']*\')|(\"[^\"]*\"))!)?\$?([a-z]{1,3})\$?(\d+)');
        //    Named Range of cells
        define('CALCULATION_REGEXP_NAMEDRANGE', '(((\w*)|(\'.*\')|(\".*\"))!)?([_A-Z][_A-Z0-9\.]*)');
    }
}

/**
 * PHPExcel_Calculation (Multiton)
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel_Calculation
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_Calculation
{
    /** Constants                */
    /** Regular Expressions        */
    //    Numeric operand
    const CALCULATION_REGEXP_NUMBER        = '[-+]?\d*\.?\d+(e[-+]?\d+)?';
    //    String operand
    const CALCULATION_REGEXP_STRING        = '"(?:[^"]|"")*"';
    //    Opening bracket
    const CALCULATION_REGEXP_OPENBRACE    = '\(';
    //    Function (allow for the old @ symbol that could be used to prefix a function, but we'll ignore it)
    const CALCULATION_REGEXP_FUNCTION    = '@?([A-Z][A-Z0-9\.]*)[\s]*\(';
    //    Cell reference (cell or range of cells, with or without a sheet reference)
    const CALCULATION_REGEXP_CELLREF    = CALCULATION_REGEXP_CELLREF;
    //    Named Range of cells
    const CALCULATION_REGEXP_NAMEDRANGE    = CALCULATION_REGEXP_NAMEDRANGE;
    //    Error
    const CALCULATION_REGEXP_ERROR        = '\#[A-Z][A-Z0_\/]*[!\?]?';


    /** constants */
    const RETURN_ARRAY_AS_ERROR = 'error';
    const RETURN_ARRAY_AS_VALUE = 'value';
    const RETURN_ARRAY_AS_ARRAY = 'array';

    private static $returnArrayAsType = self::RETURN_ARRAY_AS_VALUE;


    /**
     * Instance of this class
     *
     * @access    private
     * @var PHPExcel_Calculation
     */
    private static $instance;


    /**
     * Instance of the workbook this Calculation Engine is using
     *
     * @access    private
     * @var PHPExcel
     */
    private $workbook;

    /**
     * List of instances of the calculation engine that we've instantiated for individual workbooks
     *
     * @access    private
     * @var PHPExcel_Calculation[]
     */
    private static $workbookSets;

    /**
     * Calculation cache
     *
     * @access    private
     * @var array
     */
    private $calculationCache = array ();


    /**
     * Calculation cache enabled
     *
     * @access    private
     * @var boolean
     */
    private $calculationCacheEnabled = true;


    /**
     * List of operators that can be used within formulae
     * The true/false value indicates whether it is a binary operator or a unary operator
     *
     * @access    private
     * @var array
     */
    private static $operators = array(
        '+' => true,    '-' => true,    '*' => true,    '/' => true,
        '^' => true,    '&' => true,    '%' => false,    '~' => false,
        '>' => true,    '<' => true,    '=' => true,    '>=' => true,
        '<=' => true,    '<>' => true,    '|' => true,    ':' => true
    );

    /**
     * List of binary operators (those that expect two operands)
     *
     * @access    private
     * @var array
     */
    private static $binaryOperators = array(
        '+' => true,    '-' => true,    '*' => true,    '/' => true,
        '^' => true,    '&' => true,    '>' => true,    '<' => true,
        '=' => true,    '>=' => true,    '<=' => true,    '<>' => true,
        '|' => true,    ':' => true
    );

    /**
     * The debug log generated by the calculation engine
     *
     * @access    private
     * @var PHPExcel_CalcEngine_Logger
     *
     */
    private $debugLog;

    /**
     * Flag to determine how formula errors should be handled
     *        If true, then a user error will be triggered
     *        If false, then an exception will be thrown
     *
     * @access    public
     * @var boolean
     *
     */
    public $suppressFormulaErrors = false;

    /**
     * Error message for any error that was raised/thrown by the calculation engine
     *
     * @access    public
     * @var string
     *
     */
    public $formulaError = null;

    /**
     * An array of the nested cell references accessed by the calculation engine, used for the debug log
     *
     * @access    private
     * @var array of string
     *
     */
    private $cyclicReferenceStack;

    private $cellStack = array();

    /**
     * Current iteration counter for cyclic formulae
     * If the value is 0 (or less) then cyclic formulae will throw an exception,
     *    otherwise they will iterate to the limit defined here before returning a result
     *
     * @var integer
     *
     */
    private $cyclicFormulaCounter = 1;

    private $cyclicFormulaCell = '';

    /**
     * Number of iterations for cyclic formulae
     *
     * @var integer
     *
     */
    public $cyclicFormulaCount = 1;

    /**
     * Epsilon Precision used for comparisons in calculations
     *
     * @var float
     *
     */
    private $delta    = 0.1e-12;


    /**
     * The current locale setting
     *
     * @var string
     *
     */
    private static $localeLanguage = 'en_us';                    //    US English    (default locale)

    /**
     * List of available locale settings
     * Note that this is read for the locale subdirectory only when requested
     *
     * @var string[]
     *
     */
    private static $validLocaleLanguages = array(
        'en'        //    English        (default language)
    );

    /**
     * Locale-specific argument separator for function arguments
     *
     * @var string
     *
     */
    private static $localeArgumentSeparator = ',';
    private static $localeFunctions = array();

    /**
     * Locale-specific translations for Excel constants (True, False and Null)
     *
     * @var string[]
     *
     */
    public static $localeBoolean = array(
        'TRUE'  => 'TRUE',
        'FALSE' => 'FALSE',
        'NULL'  => 'NULL'
    );

    /**
     * Excel constant string translations to their PHP equivalents
     * Constant conversion from text name/value to actual (datatyped) value
     *
     * @var string[]
     *
     */
    private static $excelConstants = array(
        'TRUE'  => true,
        'FALSE' => false,
        'NULL'  => null
    );

     //    PHPExcel functions
    private static $PHPExcelFunctions = array(
        'ABS' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_MATH_AND_TRIG,
            'functionCall' => 'abs',
            'argumentCount' => '1'
        ),
        'ACCRINT' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_FINANCIAL,
            'functionCall' => 'PHPExcel_Calculation_Financial::ACCRINT',
            'argumentCount' => '4-7'
        ),
        'ACCRINTM' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_FINANCIAL,
            'functionCall' => 'PHPExcel_Calculation_Financial::ACCRINTM',
            'argumentCount' => '3-5'
        ),
        'ACOS' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_MATH_AND_TRIG,
            'functionCall' => 'acos',
            'argumentCount' => '1'
        ),
        'ACOSH' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_MATH_AND_TRIG,
            'functionCall' => 'acosh',
            'argumentCount' => '1'
        ),
        'ADDRESS' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_LOOKUP_AND_REFERENCE,
            'functionCall' => 'PHPExcel_Calculation_LookupRef::CELL_ADDRESS',
            'argumentCount' => '2-5'
        ),
        'AMORDEGRC' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_FINANCIAL,
            'functionCall' => 'PHPExcel_Calculation_Financial::AMORDEGRC',
            'argumentCount' => '6,7'
        ),
        'AMORLINC' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_FINANCIAL,
            'functionCall' => 'PHPExcel_Calculation_Financial::AMORLINC',
            'argumentCount' => '6,7'
        ),
        'AND' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_LOGICAL,
            'functionCall' => 'PHPExcel_Calculation_Logical::LOGICAL_AND',
            'argumentCount' => '1+'
        ),
        'AREAS' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_LOOKUP_AND_REFERENCE,
            'functionCall' => 'PHPExcel_Calculation_Functions::DUMMY',
            'argumentCount' => '1'
        ),
        'ASC' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_TEXT_AND_DATA,
            'functionCall' => 'PHPExcel_Calculation_Functions::DUMMY',
            'argumentCount' => '1'
        ),
        'ASIN' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_MATH_AND_TRIG,
            'functionCall' => 'asin',
            'argumentCount' => '1'
        ),
        'ASINH' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_MATH_AND_TRIG,
            'functionCall' => 'asinh',
            'argumentCount' => '1'
        ),
        'ATAN' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_MATH_AND_TRIG,
            'functionCall' => 'atan',
            'argumentCount' => '1'
        ),
        'ATAN2' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_MATH_AND_TRIG,
            'functionCall' => 'PHPExcel_Calculation_MathTrig::ATAN2',
            'argumentCount' => '2'
        ),
        'ATANH' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_MATH_AND_TRIG,
            'functionCall' => 'atanh',
            'argumentCount' => '1'
        ),
        'AVEDEV' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::AVEDEV',
            'argumentCount' => '1+'
        ),
        'AVERAGE' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::AVERAGE',
            'argumentCount' => '1+'
        ),
        'AVERAGEA' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::AVERAGEA',
            'argumentCount' => '1+'
        ),
        'AVERAGEIF' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::AVERAGEIF',
            'argumentCount' => '2,3'
        ),
        'AVERAGEIFS' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Functions::DUMMY',
            'argumentCount' => '3+'
        ),
        'BAHTTEXT' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_TEXT_AND_DATA,
            'functionCall' => 'PHPExcel_Calculation_Functions::DUMMY',
            'argumentCount' => '1'
        ),
        'BESSELI' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_ENGINEERING,
            'functionCall' => 'PHPExcel_Calculation_Engineering::BESSELI',
            'argumentCount' => '2'
        ),
        'BESSELJ' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_ENGINEERING,
            'functionCall' => 'PHPExcel_Calculation_Engineering::BESSELJ',
            'argumentCount' => '2'
        ),
        'BESSELK' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_ENGINEERING,
            'functionCall' => 'PHPExcel_Calculation_Engineering::BESSELK',
            'argumentCount' => '2'
        ),
        'BESSELY' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_ENGINEERING,
            'functionCall' => 'PHPExcel_Calculation_Engineering::BESSELY',
            'argumentCount' => '2'
        ),
        'BETADIST' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::BETADIST',
            'argumentCount' => '3-5'
        ),
        'BETAINV' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::BETAINV',
            'argumentCount' => '3-5'
        ),
        'BIN2DEC' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_ENGINEERING,
            'functionCall' => 'PHPExcel_Calculation_Engineering::BINTODEC',
            'argumentCount' => '1'
        ),
        'BIN2HEX' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_ENGINEERING,
            'functionCall' => 'PHPExcel_Calculation_Engineering::BINTOHEX',
            'argumentCount' => '1,2'
        ),
        'BIN2OCT' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_ENGINEERING,
            'functionCall' => 'PHPExcel_Calculation_Engineering::BINTOOCT',
            'argumentCount' => '1,2'
        ),
        'BINOMDIST' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::BINOMDIST',
            'argumentCount' => '4'
        ),
        'CEILING' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_MATH_AND_TRIG,
            'functionCall' => 'PHPExcel_Calculation_MathTrig::CEILING',
            'argumentCount' => '2'
        ),
        'CELL' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_INFORMATION,
            'functionCall' => 'PHPExcel_Calculation_Functions::DUMMY',
            'argumentCount' => '1,2'
        ),
        'CHAR' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_TEXT_AND_DATA,
            'functionCall' => 'PHPExcel_Calculation_TextData::CHARACTER',
            'argumentCount' => '1'
        ),
        'CHIDIST' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::CHIDIST',
            'argumentCount' => '2'
        ),
        'CHIINV' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::CHIINV',
            'argumentCount' => '2'
        ),
        'CHITEST' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Functions::DUMMY',
            'argumentCount' => '2'
        ),
        'CHOOSE' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_LOOKUP_AND_REFERENCE,
            'functionCall' => 'PHPExcel_Calculation_LookupRef::CHOOSE',
            'argumentCount' => '2+'
        ),
        'CLEAN' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_TEXT_AND_DATA,
            'functionCall' => 'PHPExcel_Calculation_TextData::TRIMNONPRINTABLE',
            'argumentCount' => '1'
        ),
        'CODE' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_TEXT_AND_DATA,
            'functionCall' => 'PHPExcel_Calculation_TextData::ASCIICODE',
            'argumentCount' => '1'
        ),
        'COLUMN' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_LOOKUP_AND_REFERENCE,
            'functionCall' => 'PHPExcel_Calculation_LookupRef::COLUMN',
            'argumentCount' => '-1',
            'passByReference' => array(true)
        ),
        'COLUMNS' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_LOOKUP_AND_REFERENCE,
            'functionCall' => 'PHPExcel_Calculation_LookupRef::COLUMNS',
            'argumentCount' => '1'
        ),
        'COMBIN' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_MATH_AND_TRIG,
            'functionCall' => 'PHPExcel_Calculation_MathTrig::COMBIN',
            'argumentCount' => '2'
        ),
        'COMPLEX' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_ENGINEERING,
            'functionCall' => 'PHPExcel_Calculation_Engineering::COMPLEX',
            'argumentCount' => '2,3'
        ),
        'CONCATENATE' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_TEXT_AND_DATA,
            'functionCall' => 'PHPExcel_Calculation_TextData::CONCATENATE',
            'argumentCount' => '1+'
        ),
        'CONFIDENCE' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::CONFIDENCE',
            'argumentCount' => '3'
        ),
        'CONVERT' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_ENGINEERING,
            'functionCall' => 'PHPExcel_Calculation_Engineering::CONVERTUOM',
            'argumentCount' => '3'
        ),
        'CORREL' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::CORREL',
            'argumentCount' => '2'
        ),
        'COS' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_MATH_AND_TRIG,
            'functionCall' => 'cos',
            'argumentCount' => '1'
        ),
        'COSH' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_MATH_AND_TRIG,
            'functionCall' => 'cosh',
            'argumentCount' => '1'
        ),
        'COUNT' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::COUNT',
            'argumentCount' => '1+'
        ),
        'COUNTA' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::COUNTA',
            'argumentCount' => '1+'
        ),
        'COUNTBLANK' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::COUNTBLANK',
            'argumentCount' => '1'
        ),
        'COUNTIF' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::COUNTIF',
            'argumentCount' => '2'
        ),
        'COUNTIFS' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Functions::DUMMY',
            'argumentCount' => '2'
        ),
        'COUPDAYBS' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_FINANCIAL,
            'functionCall' => 'PHPExcel_Calculation_Financial::COUPDAYBS',
            'argumentCount' => '3,4'
        ),
        'COUPDAYS' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_FINANCIAL,
            'functionCall' => 'PHPExcel_Calculation_Financial::COUPDAYS',
            'argumentCount' => '3,4'
        ),
        'COUPDAYSNC' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_FINANCIAL,
            'functionCall' => 'PHPExcel_Calculation_Financial::COUPDAYSNC',
            'argumentCount' => '3,4'
        ),
        'COUPNCD' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_FINANCIAL,
            'functionCall' => 'PHPExcel_Calculation_Financial::COUPNCD',
            'argumentCount' => '3,4'
        ),
        'COUPNUM' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_FINANCIAL,
            'functionCall' => 'PHPExcel_Calculation_Financial::COUPNUM',
            'argumentCount' => '3,4'
        ),
        'COUPPCD' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_FINANCIAL,
            'functionCall' => 'PHPExcel_Calculation_Financial::COUPPCD',
            'argumentCount' => '3,4'
        ),
        'COVAR' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::COVAR',
            'argumentCount' => '2'
        ),
        'CRITBINOM' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::CRITBINOM',
            'argumentCount' => '3'
        ),
        'CUBEKPIMEMBER' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_CUBE,
            'functionCall' => 'PHPExcel_Calculation_Functions::DUMMY',
            'argumentCount' => '?'
        ),
        'CUBEMEMBER' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_CUBE,
            'functionCall' => 'PHPExcel_Calculation_Functions::DUMMY',
            'argumentCount' => '?'
        ),
        'CUBEMEMBERPROPERTY' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_CUBE,
            'functionCall' => 'PHPExcel_Calculation_Functions::DUMMY',
            'argumentCount' => '?'
        ),
        'CUBERANKEDMEMBER' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_CUBE,
            'functionCall' => 'PHPExcel_Calculation_Functions::DUMMY',
            'argumentCount' => '?'
        ),
        'CUBESET' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_CUBE,
            'functionCall' => 'PHPExcel_Calculation_Functions::DUMMY',
            'argumentCount' => '?'
        ),
        'CUBESETCOUNT' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_CUBE,
            'functionCall' => 'PHPExcel_Calculation_Functions::DUMMY',
            'argumentCount' => '?'
        ),
        'CUBEVALUE' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_CUBE,
            'functionCall' => 'PHPExcel_Calculation_Functions::DUMMY',
            'argumentCount' => '?'
        ),
        'CUMIPMT' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_FINANCIAL,
            'functionCall' => 'PHPExcel_Calculation_Financial::CUMIPMT',
            'argumentCount' => '6'
        ),
        'CUMPRINC' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_FINANCIAL,
            'functionCall' => 'PHPExcel_Calculation_Financial::CUMPRINC',
            'argumentCount' => '6'
        ),
        'DATE' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_DATE_AND_TIME,
            'functionCall' => 'PHPExcel_Calculation_DateTime::DATE',
            'argumentCount' => '3'
        ),
        'DATEDIF' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_DATE_AND_TIME,
            'functionCall' => 'PHPExcel_Calculation_DateTime::DATEDIF',
            'argumentCount' => '2,3'
        ),
        'DATEVALUE' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_DATE_AND_TIME,
            'functionCall' => 'PHPExcel_Calculation_DateTime::DATEVALUE',
            'argumentCount' => '1'
        ),
        'DAVERAGE' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_DATABASE,
            'functionCall' => 'PHPExcel_Calculation_Database::DAVERAGE',
            'argumentCount' => '3'
        ),
        'DAY' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_DATE_AND_TIME,
            'functionCall' => 'PHPExcel_Calculation_DateTime::DAYOFMONTH',
            'argumentCount' => '1'
        ),
        'DAYS360' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_DATE_AND_TIME,
            'functionCall' => 'PHPExcel_Calculation_DateTime::DAYS360',
            'argumentCount' => '2,3'
        ),
        'DB' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_FINANCIAL,
            'functionCall' => 'PHPExcel_Calculation_Financial::DB',
            'argumentCount' => '4,5'
        ),
        'DCOUNT' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_DATABASE,
            'functionCall' => 'PHPExcel_Calculation_Database::DCOUNT',
            'argumentCount' => '3'
        ),
        'DCOUNTA' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_DATABASE,
            'functionCall' => 'PHPExcel_Calculation_Database::DCOUNTA',
            'argumentCount' => '3'
        ),
        'DDB' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_FINANCIAL,
            'functionCall' => 'PHPExcel_Calculation_Financial::DDB',
            'argumentCount' => '4,5'
        ),
        'DEC2BIN' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_ENGINEERING,
            'functionCall' => 'PHPExcel_Calculation_Engineering::DECTOBIN',
            'argumentCount' => '1,2'
        ),
        'DEC2HEX' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_ENGINEERING,
            'functionCall' => 'PHPExcel_Calculation_Engineering::DECTOHEX',
            'argumentCount' => '1,2'
        ),
        'DEC2OCT' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_ENGINEERING,
            'functionCall' => 'PHPExcel_Calculation_Engineering::DECTOOCT',
            'argumentCount' => '1,2'
        ),
        'DEGREES' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_MATH_AND_TRIG,
            'functionCall' => 'rad2deg',
            'argumentCount' => '1'
        ),
        'DELTA' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_ENGINEERING,
            'functionCall' => 'PHPExcel_Calculation_Engineering::DELTA',
            'argumentCount' => '1,2'
        ),
        'DEVSQ' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::DEVSQ',
            'argumentCount' => '1+'
        ),
        'DGET' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_DATABASE,
            'functionCall' => 'PHPExcel_Calculation_Database::DGET',
            'argumentCount' => '3'
        ),
        'DISC' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_FINANCIAL,
            'functionCall' => 'PHPExcel_Calculation_Financial::DISC',
            'argumentCount' => '4,5'
        ),
        'DMAX' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_DATABASE,
            'functionCall' => 'PHPExcel_Calculation_Database::DMAX',
            'argumentCount' => '3'
        ),
        'DMIN' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_DATABASE,
            'functionCall' => 'PHPExcel_Calculation_Database::DMIN',
            'argumentCount' => '3'
        ),
        'DOLLAR' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_TEXT_AND_DATA,
            'functionCall' => 'PHPExcel_Calculation_TextData::DOLLAR',
            'argumentCount' => '1,2'
        ),
        'DOLLARDE' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_FINANCIAL,
            'functionCall' => 'PHPExcel_Calculation_Financial::DOLLARDE',
            'argumentCount' => '2'
        ),
        'DOLLARFR' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_FINANCIAL,
            'functionCall' => 'PHPExcel_Calculation_Financial::DOLLARFR',
            'argumentCount' => '2'
        ),
        'DPRODUCT' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_DATABASE,
            'functionCall' => 'PHPExcel_Calculation_Database::DPRODUCT',
            'argumentCount' => '3'
        ),
        'DSTDEV' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_DATABASE,
            'functionCall' => 'PHPExcel_Calculation_Database::DSTDEV',
            'argumentCount' => '3'
        ),
        'DSTDEVP' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_DATABASE,
            'functionCall' => 'PHPExcel_Calculation_Database::DSTDEVP',
            'argumentCount' => '3'
        ),
        'DSUM' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_DATABASE,
            'functionCall' => 'PHPExcel_Calculation_Database::DSUM',
            'argumentCount' => '3'
        ),
        'DURATION' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_FINANCIAL,
            'functionCall' => 'PHPExcel_Calculation_Functions::DUMMY',
            'argumentCount' => '5,6'
        ),
        'DVAR' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_DATABASE,
            'functionCall' => 'PHPExcel_Calculation_Database::DVAR',
            'argumentCount' => '3'
        ),
        'DVARP' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_DATABASE,
            'functionCall' => 'PHPExcel_Calculation_Database::DVARP',
            'argumentCount' => '3'
        ),
        'EDATE' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_DATE_AND_TIME,
            'functionCall' => 'PHPExcel_Calculation_DateTime::EDATE',
            'argumentCount' => '2'
        ),
        'EFFECT' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_FINANCIAL,
            'functionCall' => 'PHPExcel_Calculation_Financial::EFFECT',
            'argumentCount' => '2'
        ),
        'EOMONTH' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_DATE_AND_TIME,
            'functionCall' => 'PHPExcel_Calculation_DateTime::EOMONTH',
            'argumentCount' => '2'
        ),
        'ERF' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_ENGINEERING,
            'functionCall' => 'PHPExcel_Calculation_Engineering::ERF',
            'argumentCount' => '1,2'
        ),
        'ERFC' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_ENGINEERING,
            'functionCall' => 'PHPExcel_Calculation_Engineering::ERFC',
            'argumentCount' => '1'
        ),
        'ERROR.TYPE' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_INFORMATION,
            'functionCall' => 'PHPExcel_Calculation_Functions::ERROR_TYPE',
            'argumentCount' => '1'
        ),
        'EVEN' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_MATH_AND_TRIG,
            'functionCall' => 'PHPExcel_Calculation_MathTrig::EVEN',
            'argumentCount' => '1'
        ),
        'EXACT' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_TEXT_AND_DATA,
            'functionCall' => 'PHPExcel_Calculation_Functions::DUMMY',
            'argumentCount' => '2'
        ),
        'EXP' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_MATH_AND_TRIG,
            'functionCall' => 'exp',
            'argumentCount' => '1'
        ),
        'EXPONDIST' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::EXPONDIST',
            'argumentCount' => '3'
        ),
        'FACT' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_MATH_AND_TRIG,
            'functionCall' => 'PHPExcel_Calculation_MathTrig::FACT',
            'argumentCount' => '1'
        ),
        'FACTDOUBLE' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_MATH_AND_TRIG,
            'functionCall' => 'PHPExcel_Calculation_MathTrig::FACTDOUBLE',
            'argumentCount' => '1'
        ),
        'FALSE' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_LOGICAL,
            'functionCall' => 'PHPExcel_Calculation_Logical::FALSE',
            'argumentCount' => '0'
        ),
        'FDIST' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Functions::DUMMY',
            'argumentCount' => '3'
        ),
        'FIND' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_TEXT_AND_DATA,
            'functionCall' => 'PHPExcel_Calculation_TextData::SEARCHSENSITIVE',
            'argumentCount' => '2,3'
        ),
        'FINDB' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_TEXT_AND_DATA,
            'functionCall' => 'PHPExcel_Calculation_TextData::SEARCHSENSITIVE',
            'argumentCount' => '2,3'
        ),
        'FINV' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Functions::DUMMY',
            'argumentCount' => '3'
        ),
        'FISHER' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::FISHER',
            'argumentCount' => '1'
        ),
        'FISHERINV' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::FISHERINV',
            'argumentCount' => '1'
        ),
        'FIXED' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_TEXT_AND_DATA,
            'functionCall' => 'PHPExcel_Calculation_TextData::FIXEDFORMAT',
            'argumentCount' => '1-3'
        ),
        'FLOOR' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_MATH_AND_TRIG,
            'functionCall' => 'PHPExcel_Calculation_MathTrig::FLOOR',
            'argumentCount' => '2'
        ),
        'FORECAST' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Statistical::FORECAST',
            'argumentCount' => '3'
        ),
        'FREQUENCY' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            'functionCall' => 'PHPExcel_Calculation_Functions::DUMMY',
            'argumentCount' => '2'
        ),
        'FTEST' => array(
            'category' => PHPExcel_Calculation_Function::CATEGORY_STATISTICAL,
            '