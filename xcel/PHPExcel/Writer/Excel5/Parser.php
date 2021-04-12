<?php

/**
 * PHPExcel_Writer_Excel5_Parser
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
 * @package    PHPExcel_Writer_Excel5
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */

// Original file header of PEAR::Spreadsheet_Excel_Writer_Parser (used as the base for this class):
// -----------------------------------------------------------------------------------------
// *  Class for parsing Excel formulas
// *
// *  License Information:
// *
// *    Spreadsheet_Excel_Writer:  A library for generating Excel Spreadsheets
// *    Copyright (c) 2002-2003 Xavier Noguer xnoguer@rezebra.com
// *
// *    This library is free software; you can redistribute it and/or
// *    modify it under the terms of the GNU Lesser General Public
// *    License as published by the Free Software Foundation; either
// *    version 2.1 of the License, or (at your option) any later version.
// *
// *    This library is distributed in the hope that it will be useful,
// *    but WITHOUT ANY WARRANTY; without even the implied warranty of
// *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
// *    Lesser General Public License for more details.
// *
// *    You should have received a copy of the GNU Lesser General Public
// *    License along with this library; if not, write to the Free Software
// *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
// */
class PHPExcel_Writer_Excel5_Parser
{
    /**    Constants                */
    // Sheet title in unquoted form
    // Invalid sheet title characters cannot occur in the sheet title:
    //         *:/\?[]
    // Moreover, there are valid sheet title characters that cannot occur in unquoted form (there may be more?)
    // +-% '^&<>=,;#()"{}
    const REGEX_SHEET_TITLE_UNQUOTED = '[^\*\:\/\\\\\?\[\]\+\-\% \\\'\^\&\<\>\=\,\;\#\(\)\"\{\}]+';

    // Sheet title in quoted form (without surrounding quotes)
    // Invalid sheet title characters cannot occur in the sheet title:
    // *:/\?[]                    (usual invalid sheet title characters)
    // Single quote is represented as a pair ''
    const REGEX_SHEET_TITLE_QUOTED = '(([^\*\:\/\\\\\?\[\]\\\'])+|(\\\'\\\')+)+';

    /**
     * The index of the character we are currently looking at
     * @var integer
     */
    public $currentCharacter;

    /**
     * The token we are working on.
     * @var string
     */
    public $currentToken;

    /**
     * The formula to parse
     * @var string
     */
    private $formula;

    /**
     * The character ahead of the current char
     * @var string
     */
    public $lookAhead;

    /**
     * The parse tree to be generated
     * @var string
     */
    private $parseTree;

    /**
     * Array of external sheets
     * @var array
     */
    private $externalSheets;

    /**
     * Array of sheet references in the form of REF structures
     * @var array
     */
    public $references;

    /**
     * The class constructor
     *
     */
    public function __construct()
    {
        $this->currentCharacter  = 0;
        $this->currentToken = '';       // The token we are working on.
        $this->formula       = '';       // The formula to parse.
        $this->lookAhead     = '';       // The character ahead of the current char.
        $this->parseTree    = '';       // The parse tree to be generated.
        $this->initializeHashes();      // Initialize the hashes: ptg's and function's ptg's
        $this->externalSheets = array();
        $this->references = array();
    }

    /**
     * Initialize the ptg and function hashes.
     *
     * @access private
     */
    private function initializeHashes()
    {
        // The Excel ptg indices
        $this->ptg = array(
            'ptgExp'       => 0x01,
            'ptgTbl'       => 0x02,
            'ptgAdd'       => 0x03,
            'ptgSub'       => 0x04,
            'ptgMul'       => 0x05,
            'ptgDiv'       => 0x06,
            'ptgPower'     => 0x07,
            'ptgConcat'    => 0x08,
            'ptgLT'        => 0x09,
            'ptgLE'        => 0x0A,
            'ptgEQ'        => 0x0B,
            'ptgGE'        => 0x0C,
            'ptgGT'        => 0x0D,
            'ptgNE'        => 0x0E,
            'ptgIsect'     => 0x0F,
            'ptgUnion'     => 0x10,
            'ptgRange'     => 0x11,
            'ptgUplus'     => 0x12,
            'ptgUminus'    => 0x13,
            'ptgPercent'   => 0x14,
            'ptgParen'     => 0x15,
            'ptgMissArg'   => 0x16,
            'ptgStr'       => 0x17,
            'ptgAttr'      => 0x19,
            'ptgSheet'     => 0x1A,
            'ptgEndSheet'  => 0x1B,
            'ptgErr'       => 0x1C,
            'ptgBool'      => 0x1D,
            'ptgInt'       => 0x1E,
            'ptgNum'       => 0x1F,
            'ptgArray'     => 0x20,
            'ptgFunc'      => 0x21,
            'ptgFuncVar'   => 0x22,
            'ptgName'      => 0x23,
            'ptgRef'       => 0x24,
            'ptgArea'      => 0x25,
            'ptgMemArea'   => 0x26,
            'ptgMemErr'    => 0x27,
            'ptgMemNoMem'  => 0x28,
            'ptgMemFunc'   => 0x29,
            'ptgRefErr'    => 0x2A,
            'ptgAreaErr'   => 0x2B,
            'ptgRefN'      => 0x2C,
            'ptgAreaN'     => 0x2D,
            'ptgMemAreaN'  => 0x2E,
            'ptgMemNoMemN' => 0x2F,
            'ptgNameX'     => 0x39,
            'ptgRef3d'     => 0x3A,
            'ptgArea3d'    => 0x3B,
            'ptgRefErr3d'  => 0x3C,
            'ptgAreaErr3d' => 0x3D,
            'ptgArrayV'    => 0x40,
            'ptgFuncV'     => 0x41,
            'ptgFuncVarV'  => 0x42,
            'ptgNameV'     => 0x43,
            'ptgRefV'      => 0x44,
            'ptgAreaV'     => 0x45,
            'ptgMemAreaV'  => 0x46,
            'ptgMemErrV'   => 0x47,
            'ptgMemNoMemV' => 0x48,
            'ptgMemFuncV'  => 0x49,
            'ptgRefErrV'   => 0x4A,
            'ptgAreaErrV'  => 0x4B,
            'ptgRefNV'     => 0x4C,
            'ptgAreaNV'    => 0x4D,
            'ptgMemAreaNV' => 0x4E,
            'ptgMemNoMemN' => 0x4F,
            'ptgFuncCEV'   => 0x58,
            'ptgNameXV'    => 0x59,
            'ptgRef3dV'    => 0x5A,
            'ptgArea3dV'   => 0x5B,
            'ptgRefErr3dV' => 0x5C,
            'ptgAreaErr3d' => 0x5D,
            'ptgArrayA'    => 0x60,
            'ptgFuncA'     => 0x61,
            'ptgFuncVarA'  => 0x62,
            'ptgNameA'     => 0x63,
            'ptgRefA'      => 0x64,
            'ptgAreaA'     => 0x65,
            'ptgMemAreaA'  => 0x66,
            'ptgMemErrA'   => 0x67,
            'ptgMemNoMemA' => 0x68,
            'ptgMemFuncA'  => 0x69,
            'ptgRefErrA'   => 0x6A,
            'ptgAreaErrA'  => 0x6B,
            'ptgRefNA'     => 0x6C,
            'ptgAreaNA'    => 0x6D,
            'ptgMemAreaNA' => 0x6E,
            'ptgMemNoMemN' => 0x6F,
            'ptgFuncCEA'   => 0x78,
            'ptgNameXA'    => 0x79,
            'ptgRef3dA'    => 0x7A,
            'ptgArea3dA'   => 0x7B,
            'ptgRefErr3dA' => 0x7C,
            'ptgAreaErr3d' => 0x7D
        );

        // Thanks to Michael Meeks and Gnumeric for the initial arg values.
        //
        // The following hash was generated by "function_locale.pl" in the distro.
        // Refer to function_locale.pl for non-English function names.
        //
        // The array elements are as follow:
        // ptg:   The Excel function ptg code.
        // args:  The number of arguments that the function takes:
        //           >=0 is a fixed number of arguments.
        //           -1  is a variable  number of arguments.
        // class: The reference, value or array class of the function args.
        // vol:   The function is volatile.
        //
        $this->functions = array(
            // function                  ptg  args  class  vol
            'COUNT'           => array(   0,   -1,    0,    0 ),
            'IF'              => array(   1,   -1,    1,    0 ),
            'ISNA'            => array(   2,    1,    1,    0 ),
            'ISERROR'         => array(   3,    1,    1,    0 ),
            'SUM'             => array(   4,   -1,    0,    0 ),
            'AVERAGE'         => array(   5,   -1,    0,    0 ),
            'MIN'             => array(   6,   -1,    0,    0 ),
            'MAX'             => array(   7,   -1,    0,    0 ),
            'ROW'             => array(   8,   -1,    0,    0 ),
            'COLUMN'          => array(   9,   -1,    0,    0 ),
            'NA'              => array(  10,    0,    0,    0 ),
            'NPV'             => array(  11,   -1,    1,    0 ),
            'STDEV'           => array(  12,   -1,    0,    0 ),
            'DOLLAR'          => array(  13,   -1,    1,    0 ),
            'FIXED'           => array(  14,   -1,    1,    0 ),
            'SIN'             => array(  15,    1,    1,    0 ),
            'COS'             => array(  16,    1,    1,    0 ),
            'TAN'             => array(  17,    1,    1,    0 ),
            'ATAN'            => array(  18,    1,    1,    0 ),
            'PI'              => array(  19,    0,    1,    0 ),
            'SQRT'            => array(  20,    1,    1,    0 ),
            'EXP'             => array(  21,    1,    1,    0 ),
            'LN'              => array(  22,    1,    1,    0 ),
            'LOG10'           => array(  23,    1,    1,    0 ),
            'ABS'             => array(  24,    1,    1,    0 ),
            'INT'             => array(  25,    1,    1,    0 ),
            'SIGN'            => array(  26,    1,    1,    0 ),
            'ROUND'           => array(  27,    2,    1,    0 ),
            'LOOKUP'          => array(  28,   -1,    0,    0 ),
            'INDEX'           => array(  29,   -1,    0,    1 ),
            'REPT'            => array(  30,    2,    1,    0 ),
            'MID'             => array(  31,    3,    1,    0 ),
            'LEN'             => array(  32,    1,    1,    0 ),
            'VALUE'           => array(  33,    1,    1,    0 ),
            'TRUE'            => array(  34,    0,    1,    0 ),
            'FALSE'           => array(  35,    0,    1,    0 ),
            'AND'             => array(  36,   -1,    0,    0 ),
            'OR'              => array(  37,   -1,    0,    0 ),
            'NOT'             => array(  38,    1,    1,    0 ),
            'MOD'             => array(  39,    2,    1,    0 ),
            'DCOUNT'          => array(  40,    3,    0,    0 ),
            'DSUM'            => array(  41,    3,    0,    0 ),
            'DAVERAGE'        => array(  42,    3,    0,    0 ),
            'DMIN'            => array(  43,    3,    0,    0 ),
            'DMAX'            => array(  44,    3,    0,    0 ),
            'DSTDEV'          => array(  45,    3,    0,    0 ),
            'VAR'             => array(  46,   -1,    0,    0 ),
            'DVAR'            => array(  47,    3,    0,    0 ),
            'TEXT'            => array(  48,    2,    1,    0 ),
            'LINEST'          => array(  49,   -1,    0,    0 ),
            'TREND'           => array(  50,   -1,    0,    0 ),
            'LOGEST'          => array(  51,   -1,    0,    0 ),
            'GROWTH'          => array(  52,   -1,    0,    0 ),
            'PV'              => array(  56,   -1,    1,    0 ),
            'FV'              => array(  57,   -1,    1,    0 ),
            'NPER'            => array(  58,   -1,    1,    0 ),
            'PMT'             => array(  59,   -1,    1,    0 ),
            'RATE'            => array(  60,   -1,    1,    0 ),
            'MIRR'            => array(  61,    3,    0,    0 ),
            'IRR'             => array(  62,   -1,    0,    0 ),
            'RAND'            => array(  63,    0,    1,    1 ),
            'MATCH'           => array(  64,   -1,    0,    0 ),
            'DATE'            => array(  65,    3,    1,    0 ),
            'TIME'            => array(  66,    3,    1,    0 ),
            'DAY'             => array(  67,    1,    1,    0 ),
            'MONTH'           => array(  68,    1,    1,    0 ),
            'YEAR'            => array(  69,    1,    1,    0 ),
            'WEEKDAY'         => array(  70,   -1,    1,    0 ),
            'HOUR'            => array(  71,    1,    1,    0 ),
            'MINUTE'          => array(  72,    1,    1,    0 ),
            'SECOND'          => array(  73,    1,    1,    0 ),
            'NOW'             => array(  74,    0,    1,    1 ),
            'AREAS'           => array(  75,    1,    0,    1 ),
            'ROWS'            => array(  76,    1,    0,    1 ),
            'COLUMNS'         => array(  77,    1,    0,    1 ),
            'OFFSET'          => array(  78,   -1,    0,    1 ),
            'SEARCH'          => array(  82,   -1,    1,    0 ),
            'TRANSPOSE'       => array(  83,    1,    1,    0 ),
            'TYPE'            => array(  86,    1,    1,    0 ),
            'ATAN2'           => array(  97,    2,    1,    0 ),
            'ASIN'            => array(  98,    1,    1,    0 ),
            'ACOS'            => array(  99,    1,    1,    0 ),
            'CHOOSE'          => array( 100,   -1,    1,    0 ),
            'HLOOKUP'         => array( 101,   -1,    0,    0 ),
            'VLOOKUP'         => array( 102,   -1,    0,    0 ),
            'ISREF'           => array( 105,    1,    0,    0 ),
            'LOG'             => array( 109,   -1,    1,    0 ),
            'CHAR'            => array( 111,    1,    1,    0 ),
            'LOWER'           => array( 112,    1,    1,    0 ),
            'UPPER'           => array( 113,    1,    1,    0 ),
            'PROPER'          => array( 114,    1,    1,    0 ),
            'LEFT'            => array( 115,   -1,    1,    0 ),
            'RIGHT'           => array( 116,   -1,    1,    0 ),
            'EXACT'           => array( 117,    2,    1,    0 ),
            'TRIM'            => array( 118,    1,    1,    0 ),
            'REPLACE'         => array( 119,    4,    1,    0 ),
            'SUBSTITUTE'      => array( 120,   -1,    1,    0 ),
            'CODE'            => array( 121,    1,    1,    0 ),
            'FIND'            => array( 124,   -1,    1,    0 ),
            'CELL'            => array( 125,   -1,    0,    1 ),
            'ISERR'           => array( 126,    1,    1,    0 ),
            'ISTEXT'          => array( 127,    1,    1,    0 ),
            'ISNUMBER'        => array( 128,    1,    1,    0 ),
            'ISBLANK'         => array( 129,    1,    1,    0 ),
            'T'               => array( 130,    1,    0,    0 ),
            'N'               => array( 131,    1,    0,    0 ),
            'DATEVALUE'       => array( 140,    1,    1,    0 ),
            'TIMEVALUE'       => array( 141,    1,    1,    0 ),
            'SLN'             => array( 142,    3,    1,    0 ),
            'SYD'             => array( 143,    4,    1,    0 ),
            'DDB'             => array( 144,   -1,    1,    0 ),
            'INDIRECT'        => array( 148,   -1,    1,    1 ),
            'CALL'            => array( 150,   -1,    1,    0 ),
            'CLEAN'           => array( 162,    1,    1,    0 ),
            'MDETERM'         => array( 163,    1,    2,    0 ),
            'MINVERSE'        => array( 164,    1,    2,    0 ),
            'MMULT'           => array( 165,    2,    2,    0 ),
            'IPMT'            => array( 167,   -1,    1,    0 ),
            'PPMT'            => array( 168,   -1,    1,    0 ),
            'COUNTA'          => array( 169,   -1,    0,    0 ),
            'PRODUCT'         => array( 183,   -1,    0,    0 ),
            'FACT'            => array( 184,    1,    1,    0 ),
            'DPRODUCT'        => array( 189,    3,    0,    0 ),
            'ISNONTEXT'       => array( 190,    1,    1,    0 ),
            'STDEVP'          => array( 193,   -1,    0,    0 ),
            'VARP'            => array( 194,   -1,    0,    0 ),
            'DSTDEVP'         => array( 195,    3,    0,    0 ),
            'DVARP'           => array( 196,    3,    0,    0 ),
            'TRUNC'           => array( 197,   -1,    1,    0 ),
            'ISLOGICAL'       => array( 198,    1,    1,    0 ),
            'DCOUNTA'         => array( 199,    3,    0,    0 ),
            'USDOLLAR'        => array( 204,   -1,    1,    0 ),
            '