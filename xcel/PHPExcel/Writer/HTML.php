<?php

/**
 * PHPExcel_Writer_HTML
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
 * @package    PHPExcel_Writer_HTML
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_Writer_HTML extends PHPExcel_Writer_Abstract implements PHPExcel_Writer_IWriter
{
    /**
     * PHPExcel object
     *
     * @var PHPExcel
     */
    protected $phpExcel;

    /**
     * Sheet index to write
     *
     * @var int
     */
    private $sheetIndex = 0;

    /**
     * Images root
     *
     * @var string
     */
    private $imagesRoot = '.';

    /**
     * embed images, or link to images
     *
     * @var boolean
     */
    private $embedImages = false;

    /**
     * Use inline CSS?
     *
     * @var boolean
     */
    private $useInlineCss = false;

    /**
     * Array of CSS styles
     *
     * @var array
     */
    private $cssStyles;

    /**
     * Array of column widths in points
     *
     * @var array
     */
    private $columnWidths;

    /**
     * Default font
     *
     * @var PHPExcel_Style_Font
     */
    private $defaultFont;

    /**
     * Flag whether spans have been calculated
     *
     * @var boolean
     */
    private $spansAreCalculated = false;

    /**
     * Excel cells that should not be written as HTML cells
     *
     * @var array
     */
    private $isSpannedCell = array();

    /**
     * Excel cells that are upper-left corner in a cell merge
     *
     * @var array
     */
    private $isBaseCell = array();

    /**
     * Excel rows that should not be written as HTML rows
     *
     * @var array
     */
    private $isSpannedRow = array();

    /**
     * Is the current writer creating PDF?
     *
     * @var boolean
     */
    protected $isPdf = false;

    /**
     * Generate the Navigation block
     *
     * @var boolean
     */
    private $generateSheetNavigationBlock = true;

    /**
     * Create a new PHPExcel_Writer_HTML
     *
     * @param    PHPExcel    $phpExcel    PHPExcel object
     