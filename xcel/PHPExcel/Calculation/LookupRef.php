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
 * PHPExcel_Calculation_LookupRef
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @category    PHPExcel
 * @package        PHPExcel_Calculation
 * @copyright    Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license        http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version        ##VERSION##, ##DATE##
 */
class PHPExcel_Calculation_LookupRef
{
    /**
     * CELL_ADDRESS
     *
     * Creates a cell address as text, given specified row and column numbers.
     *
     * Excel Function:
     *        =ADDRESS(row, column, [relativity], [referenceStyle], [sheetText])
     *
     * @param    row                Row number to use in the cell reference
     * @param    column            Column number to use in the cell reference
     * @param    relativity        Flag indicating the type of reference to return
     *                                1 or omitted    Absolute
     *                                2                Absolute row; relative column
     *                                3                Relative row; absolute column
     *                                4                Relative
     * @param    referenceStyle    A logical value that specifies the A1 or R1C1 reference style.
     *                                TRUE or omitted        CELL_ADDRESS returns an A1-style reference
     *                                FALSE                CELL_ADDRESS returns an R1C1-style reference
     * @param    sheetText        Optional Name of worksheet to use
     * @return    string
     */
    public static function CELL_ADDRESS($row, $column, $relativity = 1, $referenceStyle = true, $sheetText = '')
    {
        $row        = PHPExcel_Calculation_Functions::flattenSingleValue($row);
        $column     = PHPExcel_Calculation_Functions::flattenSingleValue($column);
        $relativity = PHPExcel_Calculation_Functions::flattenSingleValue($relativity);
        $sheetText  = PHPExcel_Calculation_Functions::flattenSingleValue($sheetText);

        if (($row < 1) || ($column < 1)) {
            return PHPExcel_Calculation_Functions::VALUE();
        }

        if ($sheetText > '') {
            if (strpos($sheetText, ' ') !== false) {
                $sheetText = "'".$sheetText."'";
            }
            $sheetText .='!';
        }
        if ((!is_bool($referenceStyle)) || $referenceStyle) {
            $rowRelative = $columnRelative = '$';
            $column = PHPExcel_Cell::stringFromColumnIndex($column-1);
            if (($relativity == 2) || ($relativity == 4)) {
                $columnRelative = '';
            }
            if (($relativity == 3) || ($relativity == 4)) {
                $rowRelative = '';
            }
            return $sheetText.$columnRelative.$column.$rowRelative.$row;
        } else {
            if (($relativity == 2) || ($relativity == 4)) {
                $column = '['.$column.']';
            }
            if (($relativity == 3) || ($relativity == 4)) {
                $row = '['.$row.']';
            }
            return $sheetText.'R'.$row.'C'.$column;
        }
    }


    /**
     * COLUMN
     *
     * Returns the column number of the given cell reference
     * If the cell reference is a range of cells, COLUMN returns the column numbers of each column in the reference as a horizontal array.
     * If cell reference is omitted, and the function is being called through the calculation engine, then it is assumed to be the
     *        reference of the cell in which the COLUMN function appears; otherwise this function returns 0.
     *
     * Excel Function:
     *        =COLUMN([cellAddress])
     *
     * @param    cellAddress        A reference to a range of cells for which you want the column numbers
     * @return    integer or array of integer
     */
    public static function COLUMN($cellAddress = null)
    {
        if (is_null($cellAddress) || trim($cellAddress) === '') {
            return 0;
        }

        if (is_array($cellAddress)) {
            foreach ($cellAddress as $columnKey => $value) {
                $columnKey = preg_replace('/[^a-z]/i', '', $columnKey);
                return (integer) PHPExcel_Cell::columnIndexFromString($columnKey);
            }
        } else {
            if (strpos($cellAddress, '!') !== false) {
                list($sheet, $cellAddress) = explode('!', $cellAddress);
            }
            if (strpos($cellAddress, ':') !== false) {
                list($startAddress, $endAddress) = explode(':', $cellAddress);
                $startAddress = preg_replace('/[^a-z]/i', '', $startAddress);
                $endAddress = preg_replace('/[^a-z]/i', '', $endAddress);
                $returnValue = array();
                do {
                    $returnValue[] = (integer) PHPExcel_Cell::columnIndexFromString($startAddress);
                } while ($startAddress++ != $endAddress);
                return $returnValue;
            } else {
                $cellAddress = preg_replace('/[^a-z]/i', '', $cellAddress);
                return (integer) PHPExcel_Cell::columnIndexFromString($cellAddress);
            }
        }
    }


    /**
     * COLUMNS
     *
     * Returns the number of columns in an array or reference.
     *
     * Excel Function:
     *        =COLUMNS(cellAddress)
     *
     * @param    cellAddress        An array or array formula, or a reference to a range of cells for which you want the number of columns
     * @return    integer            The number of columns in cellAddress
     */
    public static function COLUMNS($cellAddress = null)
    {
        if (is_null($cellAddress) || $cellAddress === '') {
            return 1;
        } elseif (!is_array($cellAddress)) {
            return PHPExcel_Calculation_Functions::VALUE();
        }

        reset($cellAddress);
        $isMatrix = (is_numeric(key($cellAddress)));
        list($columns, $rows) = PHPExcel_Calculation::_getMatrixDimensions($cellAddress);

        if ($isMatrix) {
            return $rows;
        } else {
            return $columns;
        }
    }


    /**
     * ROW
     *
     * Returns the row number of the given cell reference
     * If the cell reference is a range of cells, ROW returns the row numbers of each row in the reference as a vertical array.
     * If cell reference is omitted, and the function is being called through the calculation engine, then it is assumed to be the
     *        reference of the cell in which the ROW function appears; otherwise this function returns 0.
     *
     * Excel Function:
     *        =ROW([cellAddress])
     *
     * @param    cellAddress        A reference to a range of cells for which you want the row numbers
     * @return    integer or array of integer
     */
    public static function ROW($cellAddress = null)
    {
        if (is_null($cellAddress) || trim($cellAddress) === '') {
            return 0;
        }

        if (is_array($cellAddress)) {
            foreach ($cellAddress as $columnKey => $rowValue) {
                foreach ($rowValue as $rowKey => $cellValue) {
                    return (integer) preg_replace('/[^0-9]/i', '', $rowKey);
                }
            }
        } else {
            if (strpos($cellAddress, '!') !== false) {
                list($sheet, $cellAddress) = explode('!', $cellAddress);
            }
            if (strpos($cellAddress, ':') !== false) {
                list($startAddress, $endAddress) = explode(':', $cellAddress);
                $startAddress = preg_replace('/[^0-9]/', '', $startAddress);
                $endAddress = preg_replace('/[^0-9]/', '', $endAddress);
                $returnValue = array();
                do {
                    $returnValue[][] = (integer) $startAddress;
                } while ($startAddress++ != $endAddress);
                return $returnValue;
            } else {
                list($cellAddress) = explode(':', $cellAddress);
                return (integer) preg_replace('/[^0-9]/', '', $cellAddress);
            }
        }
    }


    /**
     * ROWS
     *
     * Returns the number of rows in an array or reference.
     *
     * Excel Function:
     *        =ROWS(cellAddress)
     *
     * @param    cellAddress        An array or array formula, or a reference to a range of cells for which you want the number of rows
     * @return    integer            The number of rows in cellAddress
     */
    public static function ROWS($cellAddress = null)
    {
        if (is_null($cellAddress) || $cellAddress === '') {
            return 1;
        } elseif (!is_array($cellAddress)) {
            return PHPExcel_Calculation_Functions::VALUE();
        }

        reset($cellAddress);
        $isMatrix = (is_numeric(key($cellAddress)));
        list($columns, $rows) = PHPExcel_Calculation::_getMatrixDimensions($cellAddress);

        if ($isMatrix) {
            return $columns;
        } else {
            return $rows;
        }
    }


    /**
     * HYPERLINK
     *
     * Excel Function:
     *        =HYPERLINK(linkURL,displayName)
     *
     * @access    public
     * @category Logical Functions
     * @param    string            $linkURL        Value to check, is also the value returned when no error
     * @param    string            $displayName    Value to return when testValue is an error condition
     * @param    PHPExcel_Cell    $pCell            The cell to set the hyperlink in
     * @return    mixed    The value of $displayName (or $linkURL if $displayName was blank)
     */
    public static function HYPERLINK($linkURL = '', $displayName = null, PHPExcel_Cell $pCell = null)
    {
        $args = func_get_args();
        $pCell = array_pop($args);

        $linkURL     = (is_null($linkURL))     ? '' : PHPExcel_Calculation_Functions::flattenSingleValue($linkURL);
        $displayName = (is_null($displayName)) ? '' : PHPExcel_Calculation_Functions::flattenSingleValue($displayName);

        if ((!is_object($pCell)) || (trim($linkURL) == '')) {
            return PHPExcel_Calculation_Functions::REF();
        }

        if ((is_object($displayName)) || trim($displayName) == '') {
            $displayName = $linkURL;
        }

        $pCell->getHyperlink()->setUrl($linkURL);
        $pCell->getHyperlink()->setTooltip($displayName);

        return $displayName;
    }


    /**
     * INDIRECT
     *
     * Returns the reference specified by a text string.
     * References are immediately evaluated to display their contents.
     *
     * Excel Function:
     *        =INDIRECT(cellAddress)
     *
     * NOTE - INDIRECT() does not yet support the optional a1 parameter introduced in Excel 2010
     *
     * @param    cellAddress        $cellAddress    The cell address of the current cell (containing this formula)
     * @param    PHPExcel_Cell    $pCell            The current cell (containing this formula)
     * @return    mixed            The cells referenced by cellAddress
     *
     * @todo    Support for the optional a1 parameter introduced in Excel 2010
     *
     */
    public static function INDIRECT($cellAddress = null, PHPExcel_Cell $pCell = null)
    {
        $cellAddress    = PHPExcel_Calculation_Functions::flattenSingleValue($cellAddress);
        if (is_null($cellAddress) || $cellAddress === '') {
            return PHPExcel_Calculation_Functions::REF();
        }

        $cellAddress1 = $cellAddress;
        $cellAddress2 = null;
        if (strpos($cellAddress, ':') !== false) {
            list($cellAddress1, $cellAddress2) = explode(':', $cellAddress);
        }

        if ((!preg_match('/^'.PHPExcel_Calculation::CALCULATION_REGEXP_CELLREF.'$/i', $cellAddress1, $matches)) ||
            ((!is_null($cellAddress2)) && (!preg_match('/^'.PHPExcel_Calculation::CALCULATION_REGEXP_CELLREF.'$/i', $cellAddress2, $matches)))) {
            if (!preg_match('/^'.PHPExcel_Calculation::CALCULATION_REGEXP_NAMEDRANGE.'$/i', $cellAddress1, $matches)) {
                return PHPExcel_Calculation_Functions::REF();
            }

            if (strpos($cellAddress, '!') !== false) {
                list($sheetName, $cellAddress) = explode('!', $cellAddress);
                $sheetName = trim($sheetName, "'");
                $pSheet = $pCell->getWorksheet()->getParent()->getSheetByName($sheetName);
            } else {
                $pSheet = $pCell->getWorksheet();
            }

            return PHPExcel_Calculation::getInstance()->extractNamedRange($cellAddress, $pSheet, false);
        }

        if (strpos($cellAddress, '!') !== false) {
            list($sheetName, $cellAddress) = explode('!', $cellAddress);
            $sheetName = trim($sheetName, "'");
            $pSheet = $pCell->getWorksheet()->getParent()->getSheetByName($sheetName);
        } else {
            $pSheet = $pCell->getWorksheet();
        }

        return PHPExcel_Calculation::getInstance()->extractCellRange($cellAddress, $pSheet, false);
    }


    /**
     * OFFSET
     *
     * Returns a reference to a range that is a specified number of rows and columns from a cell or range of cells.
     * The reference that is returned can be a single cell or a range of cells. You can specify the number of rows and
     * the number of columns to be returned.
     *
     * Excel Function:
     *        =OFFSET(cellAddress, rows, cols, [height], [width])
     *
     * @param    cellAddress        The reference from which you want to base the offset. Reference must refer to a cell or
     *                                range of adjacent cells; otherwise, OFFSET returns the #VALUE! error value.
     * @param    rows            The number of rows, up or down, that you want the upper-left cell to refer to.
     *                                Using 5 as the rows argument specifies that the upper-left cell in the reference is
     *                                five rows below reference. Rows can be positive (which means below the starting reference)
     *                                or negative (which means above the starting reference).
     * @param    cols            The number of columns, to the left or right, that you want the upper-left cell of the result
     *                                to refer to. Using 5 as the cols argument specifies that the upper-left cell in the
     *                                reference is five columns to the right of reference. Cols can be positive (which means
     *                                to the right of the starting reference) or negative (which means to the left of the
     *                                starting reference).
     * @param    height            The height, in number of rows, that you want the returned reference to be. Height must be a positive number.
     * @param    width            The width, in number of columns, that you want the returned reference to be. Width must be a positive number.
     * @return    string            A reference to a cell or range of cells
     */
   