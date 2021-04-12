<?php

/**
 * PHPExcel_Writer_Excel5_Worksheet
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

// Original file header of PEAR::Spreadsheet_Excel_Writer_Worksheet (used as the base for this class):
// -----------------------------------------------------------------------------------------
// /*
// *  Module written/ported by Xavier Noguer <xnoguer@rezebra.com>
// *
// *  The majority of this is _NOT_ my code.  I simply ported it from the
// *  PERL Spreadsheet::WriteExcel module.
// *
// *  The author of the Spreadsheet::WriteExcel module is John McNamara
// *  <jmcnamara@cpan.org>
// *
// *  I _DO_ maintain this code, and John McNamara has nothing to do with the
// *  porting of this code to PHP.  Any questions directly related to this
// *  class library should be directed to me.
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
class PHPExcel_Writer_Excel5_Worksheet extends PHPExcel_Writer_Excel5_BIFFwriter
{
    /**
     * Formula parser
     *
     * @var PHPExcel_Writer_Excel5_Parser
     */
    private $parser;

    /**
     * Maximum number of characters for a string (LABEL record in BIFF5)
     * @var integer
     */
    private $xlsStringMaxLength;

    /**
     * Array containing format information for columns
     * @var array
     */
    private $columnInfo;

    /**
     * Array containing the selected area for the worksheet
     * @var array
     */
    private $selection;

    /**
     * The active pane for the worksheet
     * @var integer
     */
    private $activePane;

    /**
     * Whether to use outline.
     * @var integer
     */
    private $outlineOn;

    /**
     * Auto outline styles.
     * @var bool
     */
    private $outlineStyle;

    /**
     * Whether to have outline summary below.
     * @var bool
     */
    private $outlineBelow;

    /**
     * Whether to have outline summary at the right.
     * @var bool
     */
    private $outlineRight;

    /**
     * Reference to the total number of strings in the workbook
     * @var integer
     */
    private $stringTotal;

    /**
     * Reference to the number of unique strings in the workbook
     * @var integer
     */
    private $stringUnique;

    /**
     * Reference to the array containing all the unique strings in the workbook
     * @var array
     */
    private $stringTable;

    /**
     * Color cache
     */
    private $colors;

    /**
     * Index of first used row (at least 0)
     * @var int
     */
    private $firstRowIndex;

    /**
     * Index of last used row. (no used rows means -1)
     * @var int
     */
    private $lastRowIndex;

    /**
     * Index of first used column (at least 0)
     * @var int
     */
    private $firstColumnIndex;

    /**
     * Index of last used column (no used columns means -1)
     * @var int
     */
    private $lastColumnIndex;

    /**
     * Sheet object
     * @var PHPExcel_Worksheet
     */
    public $phpSheet;

    /**
     * Count cell style Xfs
     *
     * @var int
     */
    private $countCellStyleXfs;

    /**
     * Escher object corresponding to MSODRAWING
     *
     * @var PHPExcel_Shared_Escher
     */
    private $escher;

    /**
     * Array of font hashes associated to FONT records index
     *
     * @var array
     */
    public $fontHashIndex;

    /**
     * Constructor
     *
     * @param int        &$str_total        Total number of strings
     * @param int        &$str_unique    Total number of unique strings
     * @param array        &$str_table        String Table
     * @param array        &$colors        Colour Table
     * @param mixed        $parser            The formula parser created for the Workbook
     * @param boolean    $preCalculateFormulas    Flag indicating whether formulas should be calculated or just written
     * @param string    $phpSheet        The worksheet to write
     * @param PHPExcel_Worksheet $phpSheet
     */
    public function __construct(&$str_total, &$str_unique, &$str_table, &$colors, $parser, $preCalculateFormulas, $phpSheet)
    {
        // It needs to call its parent's constructor explicitly
        parent::__construct();

        // change BIFFwriter limit for CONTINUE records
//        $this->_limit = 8224;


        $this->_preCalculateFormulas = $preCalculateFormulas;
        $this->stringTotal        = &$str_total;
        $this->stringUnique        = &$str_unique;
        $this->stringTable        = &$str_table;
        $this->colors            = &$colors;
        $this->parser            = $parser;

        $this->phpSheet = $phpSheet;

        //$this->ext_sheets        = array();
        //$this->offset            = 0;
        $this->xlsStringMaxLength = 255;
        $this->columnInfo  = array();
        $this->selection   = array(0,0,0,0);
        $this->activePane  = 3;

        $this->_print_headers = 0;

        $this->outlineStyle  = 0;
        $this->outlineBelow  = 1;
        $this->outlineRight  = 1;
        $this->outlineOn     = 1;

        $this->fontHashIndex = array();

        // calculate values for DIMENSIONS record
        $minR = 1;
        $minC = 'A';

        $maxR  = $this->phpSheet->getHighestRow();
        $maxC = $this->phpSheet->getHighestColumn();

        // Determine lowest and highest column and row
//        $this->firstRowIndex = ($minR > 65535) ? 65535 : $minR;
        $this->lastRowIndex = ($maxR > 65535) ? 65535 : $maxR ;

        $this->firstColumnIndex = PHPExcel_Cell::columnIndexFromString($minC);
        $this->lastColumnIndex  = PHPExcel_Cell::columnIndexFromString($maxC);

//        if ($this->firstColumnIndex > 255) $this->firstColumnIndex = 255;
        if ($this->lastColumnIndex > 255) {
            $this->lastColumnIndex = 255;
        }

        $this->countCellStyleXfs = count($phpSheet->getParent()->getCellStyleXfCollection());
    }

    /**
     * Add data to the beginning of the workbook (note the reverse order)
     * and to the end of the workbook.
     *
     * @access public
     * @see PHPExcel_Writer_Excel5_Workbook::storeWorkbook()
     */
    public function close()
    {
        $phpSheet = $this->phpSheet;

        $num_sheets = $phpSheet->getParent()->getSheetCount();

        // Write BOF record
        $this->storeBof(0x0010);

        // Write PRINTHEADERS
        $this->writePrintHeaders();

        // Write PRINTGRIDLINES
        $this->writePrintGridlines();

        // Write GRIDSET
        $this->writeGridset();

        // Calculate column widths
        $phpSheet->calculateColumnWidths();

        // Column dimensions
        if (($defaultWidth = $phpSheet->getDefaultColumnDimension()->getWidth()) < 0) {
            $defaultWidth = PHPExcel_Shared_Font::getDefaultColumnWidthByFont($phpSheet->getParent()->getDefaultStyle()->getFont());
        }

        $columnDimensions = $phpSheet->getColumnDimensions();
        $maxCol = $this->lastColumnIndex -1;
        for ($i = 0; $i <= $maxCol; ++$i) {
            $hidden = 0;
            $level = 0;
            $xfIndex = 15; // there are 15 cell style Xfs

            $width = $defaultWidth;

            $columnLetter = PHPExcel_Cell::stringFromColumnIndex($i);
            if (isset($columnDimensions[$columnLetter])) {
                $columnDimension = $columnDimensions[$columnLetter];
                if ($columnDimension->getWidth() >= 0) {
                    $width = $columnDimension->getWidth();
                }
                $hidden = $columnDimension->getVisible() ? 0 : 1;
                $level = $columnDimension->getOutlineLevel();
                $xfIndex = $columnDimension->getXfIndex() + 15; // there are 15 cell style Xfs
            }

            // Components of columnInfo:
            // $firstcol first column on the range
            // $lastcol  last column on the range
            // $width    width to set
            // $xfIndex  The optional cell style Xf index to apply to the columns
            // $hidden   The optional hidden atribute
            // $level    The optional outline level
            $this->columnInfo[] = array($i, $i, $width, $xfIndex, $hidden, $level);
        }

        // Write GUTS
        $this->writeGuts();

        // Write DEFAULTROWHEIGHT
        $this->writeDefaultRowHeight();
        // Write WSBOOL
        $this->writeWsbool();
        // Write horizontal and vertical page breaks
        $this->writeBreaks();
        // Write page header
        $this->writeHeader();
        // Write page footer
        $this->writeFooter();
        // Write page horizontal centering
        $this->writeHcenter();
        // Write page vertical centering
        $this->writeVcenter();
        // Write left margin
        $this->writeMarginLeft();
        // Write right margin
        $this->writeMarginRight();
        // Write top margin
        $this->writeMarginTop();
        // Write bottom margin
        $this->writeMarginBottom();
        // Write page setup
        $this->writeSetup();
        // Write sheet protection
        $this->writeProtect();
        // Write SCENPROTECT
        $this->writeScenProtect();
        // Write OBJECTPROTECT
        $this->writeObjectProtect();
        // Write sheet password
        $this->writePassword();
        // Write DEFCOLWIDTH record
        $this->writeDefcol();

        // Write the COLINFO records if they exist
        if (!empty($this->columnInfo)) {
            $colcount = count($this->columnInfo);
            for ($i = 0; $i < $colcount; ++$i) {
                $this->writeColinfo($this->columnInfo[$i]);
            }
        }
        $autoFilterRange = $phpSheet->getAutoFilter()->getRange();
        if (!empty($autoFilterRange)) {
            // Write AUTOFILTERINFO
            $this->writeAutoFilterInfo();
        }

        // Write sheet dimensions
        $this->writeDimensions();

        // Row dimensions
        foreach ($phpSheet->getRowDimensions() as $rowDimension) {
            $xfIndex = $rowDimension->getXfIndex() + 15; // there are 15 cellXfs
            $this->writeRow($rowDimension->getRowIndex() - 1, $rowDimension->getRowHeight(), $xfIndex, ($rowDimension->getVisible() ? '0' : '1'), $rowDimension->getOutlineLevel());
        }

        // Write Cells
        foreach ($phpSheet->getCellCollection() as $cellID) {
            $cell = $phpSheet->getCell($cellID);
            $row = $cell->getRow() - 1;
            $column = PHPExcel_Cell::columnIndexFromString($cell->getColumn()) - 1;

            // Don't break Excel!
//            if ($row + 1 > 65536 or $column + 1 > 256) {
            if ($row > 65535 || $column > 255) {
                break;
            }

            // Write cell value
            $xfIndex = $cell->getXfIndex() + 15; // there are 15 cell style Xfs

            $cVal = $cell->getValue();
            if ($cVal instanceof PHPExcel_RichText) {
                // $this->writeString($row, $column, $cVal->getPlainText(), $xfIndex);
                $arrcRun = array();
                $str_len = PHPExcel_Shared_String::CountCharacters($cVal->getPlainText(), 'UTF-8');
                $str_pos = 0;
                $elements = $cVal->getRichTextElements();
                foreach ($elements as $element) {
                    // FONT Index
                    if ($element instanceof PHPExcel_RichText_Run) {
                        $str_fontidx = $this->fontHashIndex[$element->getFont()->getHashCode()];
                    } else {
                        $str_fontidx = 0;
                    }
                    $arrcRun[] = array('strlen' => $str_pos, 'fontidx' => $str_fontidx);
                    // Position FROM
                    $str_pos += PHPExcel_Shared_String::CountCharacters($element->getText(), 'UTF-8');
                }
                $this->writeRichTextString($row, $column, $cVal->getPlainText(), $xfIndex, $arrcRun);
            } else {
                switch ($cell->getDatatype()) {
                    case PHPExcel_Cell_DataType::TYPE_STRING:
                    case PHPExcel_Cell_DataType::TYPE_NULL:
                        if ($cVal === '' || $cVal === null) {
                            $this->writeBlank($row, $column, $xfIndex);
                        } else {
                            $this->writeString($row, $column, $cVal, $xfIndex);
                        }
                        break;

                    case PHPExcel_Cell_DataType::TYPE_NUMERIC:
                        $this->writeNumber($row, $column, $cVal, $xfIndex);
                        break;

                    case PHPExcel_Cell_DataType::TYPE_FORMULA:
                        $calculatedValue = $this->_preCalculateFormulas ?
                            $cell->getCalculatedValue() : null;
                        $this->writeFormula($row, $column, $cVal, $xfIndex, $calculatedValue);
                        break;

                    case PHPExcel_Cell_DataType::TYPE_BOOL:
                        $this->writeBoolErr($row, $column, $cVal, 0, $xfIndex);
                        break;

                    case PHPExcel_Cell_DataType::TYPE_ERROR:
                        $this->writeBoolErr($row, $column, self::mapErrorCode($cVal), 1, $xfIndex);
                        break;

                }
            }
        }

        // Append
        $this->writeMsoDrawing();

        // Write WINDOW2 record
        $this->writeWindow2();

        // Write PLV record
        $this->writePageLayoutView();

        // Write ZOOM record
        $this->writeZoom();
        if ($phpSheet->getFreezePane()) {
            $this->writePanes();
        }

        // Write SELECTION record
        $this->writeSelection();

        // Write MergedCellsTable Record
        $this->writeMergedCells();

        // Hyperlinks
        foreach ($phpSheet->getHyperLinkCollection() as $coordinate => $hyperlink) {
            list($column, $row) = PHPExcel_Cell::coordinateFromString($coordinate);

            $url = $hyperlink->getUrl();

            if (strpos($url, 'sheet://') !== false) {
                // internal to current workbook
                $url = str_replace('sheet://', 'internal:', $url);

            } elseif (preg_match('/^(http:|https:|ftp:|mailto:)/', $url)) {
                // URL
                // $url = $url;

            } else {
                // external (local file)
                $url = 'external:' . $url;
            }

            $this->writeUrl($row - 1, PHPExcel_Cell::columnIndexFromString($column) - 1, $url);
        }

        $this->writeDataValidity();
        $this->writeSheetLayout();

        // Write SHEETPROTECTION record
        $this->writeSheetProtection();
        $this->writeRangeProtection();

        $arrConditionalStyles = $phpSheet->getConditionalStylesCollection();
        if (!empty($arrConditionalStyles)) {
            $arrConditional = array();
            // @todo CFRule & CFHeader
            // Write CFHEADER record
            $this->writeCFHeader();
            // Write ConditionalFormattingTable records
            foreach ($arrConditionalStyles as $cellCoordinate => $conditionalStyles) {
                foreach ($conditionalStyles as $conditional) {
                    if ($conditional->getConditionType() == PHPExcel_Style_Conditional::CONDITION_EXPRESSION
                        || $conditional->getConditionType() == PHPExcel_Style_Conditional::CONDITION_CELLIS) {
                        if (!in_array($conditional->getHashCode(), $arrConditional)) {
                            $arrConditional[] = $conditional->getHashCode();
                            // Write CFRULE record
                            $this->writeCFRule($conditional);
                        }
                    }
                }
            }
        }

        $this->storeEof();
    }

    /**
     * Write a cell range address in BIFF8
     * always fixed range
     * See section 2.5.14 in OpenOffice.org's Documentation of the Microsoft Excel File Format
     *
     * @param string $range E.g. 'A1' or 'A1:B6'
     * @return string Binary data
     */
    private function writeBIFF8CellRangeAddressFixed($range = 'A1')
    {
        $explodes = explode(':', $range);

        // extract first cell, e.g. 'A1'
        $firstCell = $explodes[0];

        // extract last cell, e.g. 'B6'
        if (count($explodes) == 1) {
            $lastCell = $firstCell;
        } else {
            $lastCell = $explodes[1];
        }

        $firstCellCoordinates = PHPExcel_Cell::coordinateFromString($firstCell); // e.g. array(0, 1)
        $lastCellCoordinates  = PHPExcel_Cell::coordinateFromString($lastCell);  // e.g. array(1, 6)

        return pack('vvvv', $firstCellCoordinates[1] - 1, $lastCellCoordinates[1] - 1, PHPExcel_Cell::columnIndexFromString($firstCellCoordinates[0]) - 1, PHPExcel_Cell::columnIndexFromString($lastCellCoordinates[0]) - 1);
    }

    /**
     * Retrieves data from memory in one chunk, or from disk in $buffer
     * sized chunks.
     *
     * @return string The data
     */
    public function getData()
    {
        $buffer = 4096;

        // Return data stored in memory
        if (isset($this->_data)) {
            $tmp   = $this->_data;
            unset($this->_data);
            return $tmp;
        }
        // No data to return
        return false;
    }

    /**
     * Set the option to print the row and column headers on the printed page.
     *
     * @access public
     * @param integer $print Whether to print the headers or not. Defaults to 1 (print).
     */
    public function printRowColHeaders($print = 1)
    {
        $this->_print_headers = $print;
    }

    /**
     * This method sets the properties for outlining and grouping. The defaults
     * correspond to Excel's defaults.
     *
     * @param bool $visible
     * @param bool $symbols_below
     * @param bool $symbols_right
     * @param bool $auto_style
     */
    public function setOutline($visible = true, $symbols_below = true, $symbols_right = true, $auto_style = false)
    {
        $this->outlineOn    = $visible;
        $this->outlineBelow = $symbols_below;
        $this->outlineRight = $symbols_right;
        $this->outlineStyle = $auto_style;

        // Ensure this is a boolean vale for Window2
        if ($this->outlineOn) {
            $this->outlineOn = 1;
        }
    }

    /**
     * Write a double to the specified row and column (zero indexed).
     * An integer can be written as a double. Excel will display an
     * integer. $format is optional.
     *
     * Returns  0 : normal termination
     *         -2 : row or column out of range
     *
     * @param integer $row    Zero indexed row
     * @param integer $col    Zero indexed column
     * @param float   $num    The number to write
     * @param mixed   $xfIndex The optional XF format
     * @return integer
     */
    private function writeNumber($row, $col, $num, $xfIndex)
    {
        $record    = 0x0203;                 // Record identifier
        $length    = 0x000E;                 // Number of bytes to follow

        $header        = pack("vv", $record, $length);
        $data        = pack("vvv", $row, $col, $xfIndex);
        $xl_double    = pack("d", $num);
        if (self::getByteOrder()) { // if it's Big Endian
            $xl_double = strrev($xl_double);
        }

        $this->append($header.$data.$xl_double);
        return(0);
    }

    /**
     * Write a LABELSST record or a LABEL record. Which one depends on BIFF version
     *
     * @param int $row Row index (0-based)
     * @param int $col Column index (0-based)
     * @param string $str The string
     * @param int $xfIndex Index to XF record
     */
    private function writeString($row, $col, $str, $xfIndex)
    {
        $this->writeLabelSst($row, $col, $str, $xfIndex);
    }

    /**
     * Write a LABELSST record or a LABEL record. Which one depends on BIFF version
     * It differs from writeString by the writing of rich text strings.
     * @param int $row Row index (0-based)
     * @param int $col Column index (0-based)
     * @param string $str The string
     * @param mixed   $xfIndex The XF format index for the cell
     * @param array $arrcRun Index to Font record and characters beginning
     */
    private function writeRichTextString($row, $col, $str, $xfIndex, $arrcRun)
    {
        $record    = 0x00FD;                   // Record identifier
        $length    = 0x000A;                   // Bytes to follow
        $str = PHPExcel_Shared_String::UTF8toBIFF8UnicodeShort($str, $arrcRun);

        /* check if string is already present */
        if (!isset($this->stringTable[$str])) {
            $this->stringTable[$str] = $this->stringUnique++;
        }
        $this->stringTotal++;

        $header    = pack('vv', $record, $length);
        $data    = pack('vvvV', $row, $col, $xfIndex, $this->stringTable[$str]);
        $this->append($header.$data);
    }

    /**
     * Write a string to the specified row and column (zero indexed).
     * NOTE: there is an Excel 5 defined limit of 255 characters.
     * $format is optional.
     * Returns  0 : normal termination
     *         -2 : row or column out of range
     *         -3 : long string truncated to 255 chars
     *
     * @access public
     * @param integer $row    Zero indexed row
     * @param integer $col    Zero indexed column
     * @param string  $str    The string to write
     * @param mixed   $xfIndex The XF format index for the cell
     * @return integer
     */
    private function writeLabel($row, $col, $str, $xfIndex)
    {
        $strlen    = strlen($str);
        $record    = 0x0204;                   // Record identifier
        $length    = 0x0008 + $strlen;         // Bytes to follow

        $str_error = 0;

        if ($strlen > $this->xlsStringMaxLength) { // LABEL must be < 255 chars
            $str    = substr($str, 0, $this->xlsStringMaxLength);
            $length    = 0x0008 + $this->xlsStringMaxLength;
            $strlen    = $this->xlsStringMaxLength;
            $str_error = -3;
        }

        $header    = pack("vv", $record, $length);
        $data    = pack("vvvv", $row, $col, $xfIndex, $strlen);
        $this->append($header . $data . $str);
        return($str_error);
    }

    /**
     * Write a string to the specified row and column (zero indexed).
     * This is the BIFF8 version (no 255 chars limit).
     * $format is optional.
     * Returns  0 : normal termination
     *         -2 : row or column out of range
     *         -3 : long string truncated to 255 chars
     *
     * @access public
     * @param integer $row    Zero indexed row
     * @param integer $col    Zero indexed column
     * @param string  $str    The string to write
     * @param mixed   $xfIndex The XF format index for the cell
     * @return integer
     */
    private function writeLabelSst($row, $col, $str, $xfIndex)
    {
        $record    = 0x00FD;                   // Record identifier
        $length    = 0x000A;                   // Bytes to follow

        $str = PHPExcel_Shared_String::UTF8toBIFF8UnicodeLong($str);

        /* check if string is already present */
        if (!isset($this->stringTable[$str])) {
            $this->stringTable[$str] = $this->stringUnique++;
        }
        $this->stringTotal++;

        $header    = pack('vv', $record, $length);
        $data    = pack('vvvV', $row, $col, $xfIndex, $this->stringTable[$str]);
        $this->append($header.$data);
    }

    /**
     * Writes a note associated with the cell given by the row and column.
     * NOTE records don't have a length limit.
     *
     * @param integer $row    Zero indexed row
     * @param integer $col    Zero indexed column
     * @param string  $note   The note to write
     */
    private function writeNote($row, $col, $note)
    {
        $note_length    = strlen($note);
        $record            = 0x001C;            // Record identifier
        $max_length        = 2048;                // Maximun length for a NOTE record

        // Length for this record is no more than 2048 + 6
        $length    = 0x0006 + min($note_length, 2048);
        $header    = pack("vv", $record, $length);
        $data    = pack("vvv", $row, $col, $note_length);
        $this->append($header . $data . substr($note, 0, 2048));

        for ($i = $max_length; $i < $note_length; $i += $max_length) {
            $chunk  = substr($note, $i, $max_length);
            $length = 0x0006 + strlen($chunk);
            $header = pack("vv", $record, $length);
            $data   = pack("vvv", -1, 0, strlen($chunk));
            $this->append($header.$data.$chunk);
        }
        return(0);
    }

    /**
     * Write a blank cell to the specified row and column (zero indexed).
     * A blank cell is used to specify formatting without adding a string
     * or a number.
     *
     * A blank cell without a format serves no purpose. Therefore, we don't write
     * a BLANK record unless a format is specified.
     *
     * Returns  0 : normal termination (including no format)
     *         -1 : insufficient number of arguments
     *         -2 : row or column out of range
     *
     * @param integer $row    Zero indexed row
     * @param integer $col    Zero indexed column
     * @param mixed   $xfIndex The XF format index
     */
    public function writeBlank($row, $col, $xfIndex)
    {
        $record    = 0x0201;                 // Record identifier
        $length    = 0x0006;                 // Number of bytes to follow

        $header    = pack("vv", $record, $length);
        $data      = pack("vvv", $row, $col, $xfIndex);
        $this->append($header . $data);
        return 0;
    }

    /**
     * Write a boolean or an error type to the specified row and column (zero indexed)
     *
     * @param int $row Row index (0-based)
     * @param int $col Column index (0-based)
     * @param int $value
     * @param boolean $isError Error or Boolean?
     * @param int $xfIndex
     */
    private function writeBoolErr($row, $col, $value, $isError, $xfIndex)
    {
        $record = 0x0205;
        $length = 8;

        $header    = pack("vv", $record, $length);
        $data      = pack("vvvCC", $row, $col, $xfIndex, $value, $isError);
        $this->append($header . $data);
        return 0;
    }

    /**
     * Write a formula to the specified row and column (zero indexed).
     * The textual representation of the formula is passed to the parser in
     * Parser.php which returns a packed binary string.
     *
     * Returns  0 : normal termination
     *         -1 : formula errors (bad formula)
     *         -2 : row or column out of range
     *
     * @param integer $row     Zero indexed row
     * @param integer $col     Zero indexed column
     * @param string  $formula The formula text string
     * @param mixed   $xfIndex  The XF format index
     * @param mixed   $calculatedValue  Calculated value
     * @return integer
     */
    private function writeFormula($row, $col, $formula, $xfIndex, $calculatedValue)
    {
        $record    = 0x0006;     // Record identifier

        // Initialize possible additional value for STRING record that should be written after the FORMULA record?
        $stringValue = null;

        // calculated value
        if (isset($calculatedValue)) {
            // Since we can't yet get the data type of the calculated value,
            // we use best effort to determine data type
            if (is_bool($calculatedValue)) {
                // Boolean value
                $num = pack('CCCvCv', 0x01, 0x00, (int)$calculatedValue, 0x00, 0x00, 0xFFFF);
            } elseif (is_int($calculatedValue) || is_float($calculatedValue)) {
                // Numeric value
                $num = pack('d', $calculatedValue);
            } elseif (is_string($calculatedValue)) {
                if (array_key_exists($calculatedValue, PHPExcel_Cell_DataType::getErrorCodes())) {
                    // Error value
                    $num = pack('CCCvCv', 0x02, 0x00, self::mapErrorCode($calculatedValue), 0x00, 0x00, 0xFFFF);
                } elseif ($calculatedValue === '') {
                    // Empty string (and BIFF8)
                    $num = pack('CCCvCv', 0x03, 0x00, 0x00, 0x00, 0x00, 0xFFFF);
                } else {
                    // Non-empty string value (or empty string BIFF5)
                    $stringValue = $calculatedValue;
                    $num = pack('CCCvCv', 0x00, 0x00, 0x00, 0x00, 0x00, 0xFFFF);
                }
            } else {
                // We are really not supposed to reach here
                $num = pack('d', 0x00);
            }
        } else {
            $num = pack('d', 0x00);
        }

        $grbit        = 0x03;                // Option flags
        $unknown    = 0x0000;            // Must be zero

        // Strip the '=' or '@' sign at the beginning of the formula string
        if ($formula{0} == '=') {
            $formula = substr($formula, 1);
        } else {
            // Error handling
            $this->writeString($row, $col, 'Unrecognised character for formula');
            return -1;
        }

        // Parse the formula using the parser in Parser.php
        try {
            $error = $this->parser->parse($formula);
            $formula = $this->parser->toReversePolish();

            $formlen    = strlen($formula);    // Length of the binary string
            $length     = 0x16 + $formlen;     // Length of the record data

            $header    = pack("vv", $record, $length);

            $data      = pack("vvv", $row, $col, $xfIndex)
                        . $num
                        . pack("vVv", $grbit, $unknown, $formlen);
            $this->append($header . $data . $formula);

            // Append also a STRING record if necessary
            if ($stringValue !== null) {
                $this->writeStringRecord($stringValue);
            }

            return 0;

        } catch (PHPExcel_Exception $e) {
            // do nothing
        }

    }

    /**
     * Write a STRING record. This
     *
     * @param string $stringValue
     */
    private function writeStringRecord($stringValue)
    {
        $record = 0x0207;     // Record identifier
        $data = PHPExcel_Shared_String::UTF8toBIFF8UnicodeLong($stringValue);

        $length = strlen($data);
        $header = pack('vv', $record, $length);

        $this->append($header . $data);
    }

    /**
     * Write a hyperlink.
     * This is comprised of two elements: the visible label and
     * the invisible link. The visible label is the same as the link unless an
     * alternative string is specified. The label is written using the
     * writeString() method. Therefore the 255 characters string limit applies.
     * $string and $format are optional.
     *
     * The hyperlink can be to a http, ftp, mail, internal sheet (not yet), or external
     * directory url.
     *
     * Returns  0 : normal termination
     *         -2 : row or column out of range
     *         -3 : long string truncated to 255 chars
     *
     * @param integer $row    Row
     * @param integer $col    Column
     * @param string  $url    URL string
     * @return integer
     */
    private function writeUrl($row, $col, $url)
    {
        // Add start row and col to arg list
        return($this->writeUrlRange($row, $col, $row, $col, $url));
    }

    /**
     * This is the more general form of writeUrl(). It allows a hyperlink to be
     * written to a range of cells. This function also decides the type of hyperlink
     * to be written. These are either, Web (http, ftp, mailto), Internal
     * (Sheet1!A1) or external ('c:\temp\foo.xls#Sheet1!A1').
     *
     * @access private
     * @see writeUrl()
     * @param integer $row1   Start row
     * @param integer $col1   Start column
     * @param integer $row2   End row
     * @param integer $col2   End column
     * @param string  $url    URL string
     * @return integer
     */
    public function writeUrlRange($row1, $col1, $row2, $col2, $url)
    {
        // Check for internal/external sheet links or default to web link
        if (preg_match('[^internal:]', $url)) {
            return($this->writeUrlInternal($row1, $col1, $row2, $col2, $url));
        }
        if (preg_match('[^external:]', $url)) {
            return($this->writeUrlExternal($row1, $col1, $row2, $col2, $url));
        }
        return($this->writeUrlWeb($row1, $col1, $row2, $col2, $url));
    }

    /**
     * Used to write http, ftp and mailto hyperlinks.
     * The link type ($options) is 0x03 is the same as absolute dir ref without
     * sheet. However it is differentiated by the $unknown2 data stream.
     *
     * @access private
     * @see writeUrl()
     * @param integer $row1   Start row
     * @param integer $col1   Start column
     * @param integer $row2   End row
     * @param integer $col2   End column
     * @param string  $url    URL string
     * @return integer
     */
    public function writeUrlWeb($row1, $col1, $row2, $col2, $url)
    {
        $record      = 0x01B8;                       // Record identifier
        $length      = 0x00000;                      // Bytes to follow

        // Pack the undocumented parts of the hyperlink stream
        $unknown1    = pack("H*", "D0C9EA79F9BACE118C8200AA004BA90B02000000");
        $unknown2    = pack("H*", "E0C9EA79F9BACE118C8200AA004BA90B");

        // Pack the option flags
        $options     = pack("V", 0x03);

        // Convert URL to a null terminated wchar string
        $url         = join("\0", preg_split("''", $url, -1, PREG_SPLIT_NO_EMPTY));
        $url         = $url . "\0\0\0";

        // Pack the length of the URL
        $url_len     = pack("V", strlen($url));

        // Calculate the data length
        $length      = 0x34 + strlen($url);

        // Pack the header data
        $header      = pack("vv", $record, $length);
        $data        = pack("vvvv", $row1, $row2, $col1, $col2);

        // Write the packed data
        $this->append($header . $data .
                       $unknown1 . $options .
                       $unknown2 . $url_len . $url);
        return 0;
    }

    /**
     * Used to write internal reference hyperlinks such as "Sheet1!A1".
     *
     * @access private
     * @see writeUrl()
     * @param integer $row1   Start 