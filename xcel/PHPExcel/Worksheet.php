<?php

/**
 * PHPExcel_Worksheet
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
 * @package    PHPExcel_Worksheet
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##VERSION##, ##DATE##
 */
class PHPExcel_Worksheet implements PHPExcel_IComparable
{
    /* Break types */
    const BREAK_NONE   = 0;
    const BREAK_ROW    = 1;
    const BREAK_COLUMN = 2;

    /* Sheet state */
    const SHEETSTATE_VISIBLE    = 'visible';
    const SHEETSTATE_HIDDEN     = 'hidden';
    const SHEETSTATE_VERYHIDDEN = 'veryHidden';

    /**
     * Invalid characters in sheet title
     *
     * @var array
     */
    private static $invalidCharacters = array('*', ':', '/', '\\', '?', '[', ']');

    /**
     * Parent spreadsheet
     *
     * @var PHPExcel
     */
    private $parent;

    /**
     * Cacheable collection of cells
     *
     * @var PHPExcel_CachedObjectStorage_xxx
     */
    private $cellCollection;

    /**
     * Collection of row dimensions
     *
     * @var PHPExcel_Worksheet_RowDimension[]
     */
    private $rowDimensions = array();

    /**
     * Default row dimension
     *
     * @var PHPExcel_Worksheet_RowDimension
     */
    private $defaultRowDimension;

    /**
     * Collection of column dimensions
     *
     * @var PHPExcel_Worksheet_ColumnDimension[]
     */
    private $columnDimensions = array();

    /**
     * Default column dimension
     *
     * @var PHPExcel_Worksheet_ColumnDimension
     */
    private $defaultColumnDimension = null;

    /**
     * Collection of drawings
     *
     * @var PHPExcel_Worksheet_BaseDrawing[]
     */
    private $drawingCollection = null;

    /**
     * Collection of Chart objects
     *
     * @var PHPExcel_Chart[]
     */
    private $chartCollection = array();

    /**
     * Worksheet title
     *
     * @var string
     */
    private $title;

    /**
     * Sheet state
     *
     * @var string
     */
    private $sheetState;

    /**
     * Page setup
     *
     * @var PHPExcel_Worksheet_PageSetup
     */
    private $pageSetup;

    /**
     * Page margins
     *
     * @var PHPExcel_Worksheet_PageMargins
     */
    private $pageMargins;

    /**
     * Page header/footer
     *
     * @var PHPExcel_Worksheet_HeaderFooter
     */
    private $headerFooter;

    /**
     * Sheet view
     *
     * @var PHPExcel_Worksheet_SheetView
     */
    private $sheetView;

    /**
     * Protection
     *
     * @var PHPExcel_Worksheet_Protection
     */
    private $protection;

    /**
     * Collection of styles
     *
     * @var PHPExcel_Style[]
     */
    private $styles = array();

    /**
     * Conditional styles. Indexed by cell coordinate, e.g. 'A1'
     *
     * @var array
     */
    private $conditionalStylesCollection = array();

    /**
     * Is the current cell collection sorted already?
     *
     * @var boolean
     */
    private $cellCollectionIsSorted = false;

    /**
     * Collection of breaks
     *
     * @var array
     */
    private $breaks = array();

    /**
     * Collection of merged cell ranges
     *
     * @var array
     */
    private $mergeCells = array();

    /**
     * Collection of protected cell ranges
     *
     * @var array
     */
    private $protectedCells = array();

    /**
     * Autofilter Range and selection
     *
     * @var PHPExcel_Worksheet_AutoFilter
     */
    private $autoFilter;

    /**
     * Freeze pane
     *
     * @var string
     */
    private $freezePane = '';

    /**
     * Show gridlines?
     *
     * @var boolean
     */
    private $showGridlines = true;

    /**
    * Print gridlines?
    *
    * @var boolean
    */
    private $printGridlines = false;

    /**
    * Show row and column headers?
    *
    * @var boolean
    */
    private $showRowColHeaders = true;

    /**
     * Show summary below? (Row/Column outline)
     *
     * @var boolean
     */
    private $showSummaryBelow = true;

    /**
     * Show summary right? (Row/Column outline)
     *
     * @var boolean
     */
    private $showSummaryRight = true;

    /**
     * Collection of comments
     *
     * @var PHPExcel_Comment[]
     */
    private $comments = array();

    /**
     * Active cell. (Only one!)
     *
     * @var string
     */
    private $activeCell = 'A1';

    /**
     * Selected cells
     *
     * @var string
     */
    private $selectedCells = 'A1';

    /**
     * Cached highest column
     *
     * @var string
     */
    private $cachedHighestColumn = 'A';

    /**
     * Cached highest row
     *
     * @var int
     */
    private $cachedHighestRow = 1;

    /**
     * Right-to-left?
     *
     * @var boolean
     */
    private $rightToLeft = false;

    /**
     * Hyperlinks. Indexed by cell coordinate, e.g. 'A1'
     *
     * @var array
     */
    private $hyperlinkCollection = array();

    /**
     * Data validation objects. Indexed by cell coordinate, e.g. 'A1'
     *
     * @var array
     */
    private $dataValidationCollection = array();

    /**
     * Tab color
     *
     * @var PHPExcel_Style_Color
     */
    private $tabColor;

    /**
     * Dirty flag
     *
     * @var boolean
     */
    private $dirty = true;

    /**
     * Hash
     *
     * @var string
     */
    private $hash;

    /**
    * CodeName
    *
    * @var string
    */
    private $codeName = null;

    /**
     * Create a new worksheet
     *
     * @param PHPExcel        $pParent
     * @param string        $pTitle
     */
    public function __construct(PHPExcel $pParent = null, $pTitle = 'Worksheet')
    {
        // Set parent and title
        $this->parent = $pParent;
        $this->setTitle($pTitle, false);
        // setTitle can change $pTitle
        $this->setCodeName($this->getTitle());
        $this->setSheetState(PHPExcel_Worksheet::SHEETSTATE_VISIBLE);

        $this->cellCollection         = PHPExcel_CachedObjectStorageFactory::getInstance($this);
        // Set page setup
        $this->pageSetup              = new PHPExcel_Worksheet_PageSetup();
        // Set page margins
        $this->pageMargins            = new PHPExcel_Worksheet_PageMargins();
        // Set page header/footer
        $this->headerFooter           = new PHPExcel_Worksheet_HeaderFooter();
        // Set sheet view
        $this->sheetView              = new PHPExcel_Worksheet_SheetView();
        // Drawing collection
        $this->drawingCollection      = new ArrayObject();
        // Chart collection
        $this->chartCollection        = new ArrayObject();
        // Protection
        $this->protection             = new PHPExcel_Worksheet_Protection();
        // Default row dimension
        $this->defaultRowDimension    = new PHPExcel_Worksheet_RowDimension(null);
        // Default column dimension
        $this->defaultColumnDimension = new PHPExcel_Worksheet_ColumnDimension(null);
        $this->autoFilter             = new PHPExcel_Worksheet_AutoFilter(null, $this);
    }


    /**
     * Disconnect all cells from this PHPExcel_Worksheet object,
     *    typically so that the worksheet object can be unset
     *
     */
    public function disconnectCells()
    {
        if ($this->cellCollection !== null) {
            $this->cellCollection->unsetWorksheetCells();
            $this->cellCollection = null;
        }
        //    detach ourself from the workbook, so that it can then delete this worksheet successfully
        $this->parent = null;
    }

    /**
     * Code to execute when this worksheet is unset()
     *
     */
    public function __destruct()
    {
        PHPExcel_Calculation::getInstance($this->parent)->clearCalculationCacheForWorksheet($this->title);

        $this->disconnectCells();
    }

   /**
     * Return the cache controller for the cell collection
     *
     * @return PHPExcel_CachedObjectStorage_xxx
     */
    public function getCellCacheController()
    {
        return $this->cellCollection;
    }


    /**
     * Get array of invalid characters for sheet title
     *
     * @return array
     */
    public static function getInvalidCharacters()
    {
        return self::$invalidCharacters;
    }

    /**
     * Check sheet code name for valid Excel syntax
     *
     * @param string $pValue The string to check
     * @return string The valid string
     * @throws Exception
     */
    private static function checkSheetCodeName($pValue)
    {
        $CharCount = PHPExcel_Shared_String::CountCharacters($pValue);
        if ($CharCount == 0) {
            throw new PHPExcel_Exception('Sheet code name cannot be empty.');
        }
        // Some of the printable ASCII characters are invalid:  * : / \ ? [ ] and  first and last characters cannot be a "'"
        if ((str_replace(self::$invalidCharacters, '', $pValue) !== $pValue) ||
            (PHPExcel_Shared_String::Substring($pValue, -1, 1)=='\'') ||
            (PHPExcel_Shared_String::Substring($pValue, 0, 1)=='\'')) {
            throw new PHPExcel_Exception('Invalid character found in sheet code name');
        }

        // Maximum 31 characters allowed for sheet title
        if ($CharCount > 31) {
            throw new PHPExcel_Exception('Maximum 31 characters allowed in sheet code name.');
        }

        return $pValue;
    }

   /**
     * Check sheet title for valid Excel syntax
     *
     * @param string $pValue The string to check
     * @return string The valid string
     * @throws PHPExcel_Exception
     */
    private static function checkSheetTitle($pValue)
    {
        // Some of the printable ASCII characters are invalid:  * : / \ ? [ ]
        if (str_replace(self::$invalidCharacters, '', $pValue) !== $pValue) {
            throw new PHPExcel_Exception('Invalid character found in sheet title');
        }

        // Maximum 31 characters allowed for sheet title
        if (PHPExcel_Shared_String::CountCharacters($pValue) > 31) {
            throw new PHPExcel_Exception('Maximum 31 characters allowed in sheet title.');
        }

        return $pValue;
    }

    /**
     * Get collection of cells
     *
     * @param boolean $pSorted Also sort the cell collection?
     * @return PHPExcel_Cell[]
     */
    public function getCellCollection($pSorted = true)
    {
        if ($pSorted) {
            // Re-order cell collection
            return $this->sortCellCollection();
        }
        if ($this->cellCollection !== null) {
            return $this->cellCollection->getCellList();
        }
        return array();
    }

    /**
     * Sort collection of cells
     *
     * @return PHPExcel_Worksheet
     */
    public function sortCellCollection()
    {
        if ($this->cellCollection !== null) {
            return $this->cellCollection->getSortedCellList();
        }
        return array();
    }

    /**
     * Get collection of row dimensions
     *
     * @return PHPExcel_Worksheet_RowDimension[]
     */
    public function getRowDimensions()
    {
        return $this->rowDimensions;
    }

    /**
     * Get default row dimension
     *
     * @return PHPExcel_Worksheet_RowDimension
     */
    public function getDefaultRowDimension()
    {
        return $this->defaultRowDimension;
    }

    /**
     * Get collection of column dimensions
     *
     * @return PHPExcel_Worksheet_ColumnDimension[]
     */
    public function getColumnDimensions()
    {
        return $this->columnDimensions;
    }

    /**
     * Get default column dimension
     *
     * @return PHPExcel_Worksheet_ColumnDimension
     */
    public function getDefaultColumnDimension()
    {
        return $this->defaultColumnDimension;
    }

    /**
     * Get collection of drawings
     *
     * @return PHPExcel_Worksheet_BaseDrawing[]
     */
    public function getDrawingCollection()
    {
        return $this->drawingCollection;
    }

    /**
     * Get collection of charts
     *
     * @return PHPExcel_Chart[]
     */
    public function getChartCollection()
    {
        return $this->chartCollection;
    }

    /**
     * Add chart
     *
     * @param PHPExcel_Chart $pChart
     * @param int|null $iChartIndex Index where chart should go (0,1,..., or null for last)
     * @return PHPExcel_Chart
     */
    public f