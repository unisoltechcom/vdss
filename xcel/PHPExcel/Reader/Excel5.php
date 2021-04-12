<?php

/** PHPExcel root directory */
if (!defined('PHPEXCEL_ROOT')) {
    /**
     * @ignore
     */
    define('PHPEXCEL_ROOT', dirname(__FILE__) . '/../../');
    require(PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php');
}

/**
 * PHPExcel_Reader_Excel5
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
 * @package    PHPExcel_Reader_Excel5
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */

// Original file header of ParseXL (used as the base for this class):
// --------------------------------------------------------------------------------
// Adapted from Excel_Spreadsheet_Reader developed by users bizon153,
// trex005, and mmp11 (SourceForge.net)
// http://sourceforge.net/projects/phpexcelreader/
// Primary changes made by canyoncasa (dvc) for ParseXL 1.00 ...
//     Modelled moreso after Perl Excel Parse/Write modules
//     Added Parse_Excel_Spreadsheet object
//         Reads a whole worksheet or tab as row,column array or as
//         associated hash of indexed rows and named column fields
//     Added variables for worksheet (tab) indexes and names
//     Added an object call for loading individual woorksheets
//     Changed default indexing defaults to 0 based arrays
//     Fixed date/time and percent formats
//     Includes patches found at SourceForge...
//         unicode patch by nobody
//         unpack("d") machine depedency patch by matchy
//         boundsheet utf16 patch by bjaenichen
//     Renamed functions for shorter names
//     General code cleanup and rigor, including <80 column width
//     Included a testcase Excel file and PHP example calls
//     Code works for PHP 5.x

// Primary changes made by canyoncasa (dvc) for ParseXL 1.10 ...
// http://sourceforge.net/tracker/index.php?func=detail&aid=1466964&group_id=99160&atid=623334
//     Decoding of formula conditions, results, and tokens.
//     Support for user-defined named cells added as an array "namedcells"
//         Patch code for user-defined named cells supports single cells only.
//         NOTE: this patch only works for BIFF8 as BIFF5-7 use a different
//         external sheet reference structure
class PHPExcel_Reader_Excel5 extends PHPExcel_Reader_Abstract implements PHPExcel_Reader_IReader
{
    // ParseXL definitions
    const XLS_BIFF8                     = 0x0600;
    const XLS_BIFF7                     = 0x0500;
    const XLS_WorkbookGlobals           = 0x0005;
    const XLS_Worksheet                 = 0x0010;

    // record identifiers
    const XLS_TYPE_FORMULA              = 0x0006;
    const XLS_TYPE_EOF                  = 0x000a;
    const XLS_TYPE_PROTECT              = 0x0012;
    const XLS_TYPE_OBJECTPROTECT        = 0x0063;
    const XLS_TYPE_SCENPROTECT          = 0x00dd;
    const XLS_TYPE_PASSWORD             = 0x0013;
    const XLS_TYPE_HEADER               = 0x0014;
    const XLS_TYPE_FOOTER               = 0x0015;
    const XLS_TYPE_EXTERNSHEET          = 0x0017;
    const XLS_TYPE_DEFINEDNAME          = 0x0018;
    const XLS_TYPE_VERTICALPAGEBREAKS   = 0x001a;
    const XLS_TYPE_HORIZONTALPAGEBREAKS = 0x001b;
    const XLS_TYPE_NOTE                 = 0x001c;
    const XLS_TYPE_SELECTION            = 0x001d;
    const XLS_TYPE_DATEMODE             = 0x0022;
    const XLS_TYPE_EXTERNNAME           = 0x0023;
    const XLS_TYPE_LEFTMARGIN           = 0x0026;
    const XLS_TYPE_RIGHTMARGIN          = 0x0027;
    const XLS_TYPE_TOPMARGIN            = 0x0028;
    const XLS_TYPE_BOTTOMMARGIN         = 0x0029;
    const XLS_TYPE_PRINTGRIDLINES       = 0x002b;
    const XLS_TYPE_FILEPASS             = 0x002f;
    const XLS_TYPE_FONT                 = 0x0031;
    const XLS_TYPE_CONTINUE             = 0x003c;
    const XLS_TYPE_PANE                 = 0x0041;
    const XLS_TYPE_CODEPAGE             = 0x0042;
    const XLS_TYPE_DEFCOLWIDTH          = 0x0055;
    const XLS_TYPE_OBJ                  = 0x005d;
    const XLS_TYPE_COLINFO              = 0x007d;
    const XLS_TYPE_IMDATA               = 0x007f;
    const XLS_TYPE_SHEETPR              = 0x0081;
    const XLS_TYPE_HCENTER              = 0x0083;
    const XLS_TYPE_VCENTER              = 0x0084;
    const XLS_TYPE_SHEET                = 0x0085;
    const XLS_TYPE_PALETTE              = 0x0092;
    const XLS_TYPE_SCL                  = 0x00a0;
    const XLS_TYPE_PAGESETUP            = 0x00a1;
    const XLS_TYPE_MULRK                = 0x00bd;
    const XLS_TYPE_MULBLANK             = 0x00be;
    const XLS_TYPE_DBCELL               = 0x00d7;
    const XLS_TYPE_XF                   = 0x00e0;
    const XLS_TYPE_MERGEDCELLS          = 0x00e5;
    const XLS_TYPE_MSODRAWINGGROUP      = 0x00eb;
    const XLS_TYPE_MSODRAWING           = 0x00ec;
    const XLS_TYPE_SST                  = 0x00fc;
    const XLS_TYPE_LABELSST             = 0x00fd;
    const XLS_TYPE_EXTSST               = 0x00ff;
    const XLS_TYPE_EXTERNALBOOK         = 0x01ae;
    const XLS_TYPE_DATAVALIDATIONS      = 0x01b2;
    const XLS_TYPE_TXO                  = 0x01b6;
    const XLS_TYPE_HYPERLINK            = 0x01b8;
    const XLS_TYPE_DATAVALIDATION       = 0x01be;
    const XLS_TYPE_DIMENSION            = 0x0200;
    const XLS_TYPE_BLANK                = 0x0201;
    const XLS_TYPE_NUMBER               = 0x0203;
    const XLS_TYPE_LABEL                = 0x0204;
    const XLS_TYPE_BOOLERR              = 0x0205;
    const XLS_TYPE_STRING               = 0x0207;
    const XLS_TYPE_ROW                  = 0x0208;
    const XLS_TYPE_INDEX                = 0x020b;
    const XLS_TYPE_ARRAY                = 0x0221;
    const XLS_TYPE_DEFAULTROWHEIGHT     = 0x0225;
    const XLS_TYPE_WINDOW2              = 0x023e;
    const XLS_TYPE_RK                   = 0x027e;
    const XLS_TYPE_STYLE                = 0x0293;
    const XLS_TYPE_FORMAT               = 0x041e;
    const XLS_TYPE_SHAREDFMLA           = 0x04bc;
    const XLS_TYPE_BOF                  = 0x0809;
    const XLS_TYPE_SHEETPROTECTION      = 0x0867;
    const XLS_TYPE_RANGEPROTECTION      = 0x0868;
    const XLS_TYPE_SHEETLAYOUT          = 0x0862;
    const XLS_TYPE_XFEXT                = 0x087d;
    const XLS_TYPE_PAGELAYOUTVIEW       = 0x088b;
    const XLS_TYPE_UNKNOWN              = 0xffff;

    // Encryption type
    const MS_BIFF_CRYPTO_NONE           = 0;
    const MS_BIFF_CRYPTO_XOR            = 1;
    const MS_BIFF_CRYPTO_RC4            = 2;
    
    // Si