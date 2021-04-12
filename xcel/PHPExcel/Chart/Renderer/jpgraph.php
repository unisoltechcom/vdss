<?php

require_once(PHPExcel_Settings::getChartRendererPath().'/jpgraph.php');

/**
 * PHPExcel_Chart_Renderer_jpgraph
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
 * @category    PHPExcel
 * @package        PHPExcel_Chart_Renderer
 * @copyright    Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license        http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version        ##VERSION##, ##DATE##
 */
class PHPExcel_Chart_Renderer_jpgraph
{
    private static $width    = 640;

    private static $height    = 480;

    private static $colourSet = array(
        'mediumpurple1',    'palegreen3',     'gold1',          'cadetblue1',
        'darkmagenta',      'coral',          'dodgerblue3',    'eggplant',
        'mediumblue',       'magenta',        'sandybrown',     'cyan',
        'firebrick1',       'forestgreen',    'deeppink4',      'darkolivegreen',
        'goldenrod2'
    );

    private static $markSet = array(
        'diamond'  => MARK_DIAMOND,
        'square'   => MARK_SQUARE,
        'triangle' => MARK_UTRIANGLE,
        'x'        => MARK_X,
        'star'     => MARK_STAR,
        'dot'      => MARK_FILLEDCIRCLE,
        'dash'     => MARK_DTRIANGLE,
        'circle'   => MARK_CIRCLE,
        'plus'     => MARK_CROSS
    );


    private $chart;

    private $graph;

    private static $plotColour = 0;

    private static $plotMark = 0;


    private function formatPointMarker($seriesPlot, $markerID)
    {
        $plotMarkKeys = array_keys(self::$markSet);
        if (is_null($markerID)) {
            //    Use default plot marker (next marker in the series)
            self::$plotMark %= count(self::$markSet);
            $seriesPlot->mark->SetType(self::$markSet[$plotMarkKeys[self::$plotMark++]]);
        } elseif ($markerID !== 'none') {
            //    Use specified plot marker (if it exists)
            if (isset(self::$markSet[$markerID])) {
                $seriesPlot->mark->SetType(self::$markSet[$markerID]);
            } else {
                //    If the specified plot marker doesn't exist, use default plot marker (next marker in the series)
                self::$plotMark %= count(self::$markSet);
                $seriesPlot->mark->SetType(self::$markSet[$plotMarkKeys[self::$plotMark++]]);
            }
        } else {
            //    Hide plot marker
            $seriesPlot->mark->Hide();
        }
        $seriesPlot->mark->SetColor(self::$colourSet[self::$plotColour]);
        $seriesPlot->mark->SetFillColor(self::$colourSet[self::$plotColour]);
        $seriesPlot->SetColor(self::$colourSet[self::$plotColour++]);

        return $seriesPlot;
    }


    private function formatDataSetLabels($groupID, $datasetLabels, $labelCount, $rotation = '')
    {
        $datasetLabelFormatCode = $this->chart->getPlotArea()->getPlotGroupByIndex($groupID)->getPlotCategoryByIndex(0)->getFormatCode();
        if (!is_null($datasetLabelFormatCode)) {
            //    Retrieve any label formatting code
            $datasetLabelFormatCode = stripslashes($datasetLabelFormatCode);
        }

        $testCurrentIndex = 0;
        foreach ($datasetLabels as $i => $datasetLabel) {
            if (is_array($datasetLabel)) {
                if ($rotation == 'bar') {
                    $datasetLabels[$i] = implode(" ", $datasetLabel);
                } else {
                    $datasetLabel = array_reverse($datasetLabel);
                    $datasetLabels[$i] = implode("\n", $datasetLabel);
                }
            } else {
                //    Format labels according to any formatting code
                if (!is_null($datasetLabelFormatCode)) {
                    $datasetLabels[$i] = PHPExcel_Style_NumberFormat::toFormattedString($datasetLabel, $datasetLabelFormatCode);
                }
            }
            ++$testCurrentIndex;
        }

        return $datasetLabels;
    }


    private function percentageSumCalculation($groupID, $seriesCount)
    {
        //    Adjust our values to a percentage value across all series in the group
        for ($i = 0; $i < $seriesCount; ++$i) {
            if ($i == 0) {
                $sumValues = $this->chart->getPlotArea()->getPlotGroupByIndex($groupID)->getPlotValuesByIndex($i)->getDataValues();
            } else {
                $nextValues = $this->chart->getPlotArea()->getPlotGroupByIndex($groupID)->getPlotValuesByIndex($i)->getDataValues();
                foreach ($nextValues as $k => $value) {
                    if (isset($sumValues[$k])) {
                        $sumValues[$k] += $value;
                    } else {
                        $sumValues[$k] = $value;
                    }
                }
            }
        }

        return $sumValues;
    }


    private function percentageAdjustValues($dataValues, $sumValues)
    {
        foreach ($dataValues as $k => $dataValue) {
            $dataValues[$k] = $dataValue / $sumValues[$k] * 100;
        }

        return $dataValues;
    }


    private function getCaption($captionElement)
    {
        //    Read any caption
        $caption = (!is_null($captionElement)) ? $captionElement->getCaption() : null;
        //    Test if we have a title caption to display
        if (!is_null($caption)) {
            //    If we do, it could be a plain string or an array
            if (is_array($caption)) {
                //    Implode an array to a plain string
                $caption = implode('', $caption);
            }
        }
        return $caption;
    }


    private function renderTitle()
    {
        $title = $this->getCaption($this->chart->getTitle());
        if (!is_null($title)) {
            $this->graph->title->Set($title);
        }
    }


    private function renderLegend()
    {
        $legend = $this->chart->getLegend();
        if (!is_null($legend)) {
            $legendPosition = $legend->getPosition();
            $legendOverlay = $legend->getOverlay();
            switch ($legendPosition) {
                case 'r':
                    $this->graph->legend->SetPos(0.01, 0.5, 'right', 'center');    //    right
                    $this->graph->legend->SetColumns(1);
                    break;
                case 'l':
                    $this->graph->legend->SetPos(0.01, 0.5, 'left', 'center');    //    left
                    $this->graph->legend->SetColumns(1);
                    break;
                case 't':
                    $this->graph->legend->SetPos(0.5, 0.01, 'center', 'top');    //    top
                    break;
                case 'b':
                    $this->graph->legend->SetPos(0.5, 0.99, 'center', 'bottom');    //    bottom
                    break;
                default:
                    $this->graph->legend->SetPos(0.01, 0.01, 'right', 'top');    //    top-right
                    $this->graph->legend->SetColumns(1);
                    break;
            }
        } else {
            $this->graph->legend->Hide();
        }
    }


    private function renderCartesianPlotArea($type = 'textlin')
    {
        $this->graph = new Graph(self::$width, self::$height);
        $this->graph->SetScale($type);

        $this->renderTitle();

        //    Rotate for bar rather than column chart
        $rotation = $this->chart->getPlotArea()->getPlotGroupByIndex(0)->getPlotDirection();
        $reverse = ($rotation == 'bar') ? true : false;

        $xAxisLabel = $this->chart->getXAxisLabel();
        if (!is_null($xAxisLabel)) {
            $title = $this->getCaption($xAxisLabel);
            if (!is_null($title)) {
                $this->graph->xaxis->SetTitle($title, 'center');
                $this->graph->xaxis->title->SetMargin(35);
                if ($reverse) {
                    $this->graph->xaxis->title->SetAngle(90);
                    $this->graph->xaxis->title->SetMargin(90);
                }
            }
        }

        $yAxisLabel = $this->chart->getYAxisLabel