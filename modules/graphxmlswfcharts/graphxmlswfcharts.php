<?php

/**
  * Statistics
  * @category stats
  *
  * @author Damien Metzger / Epitech
  * @copyright Epitech / PrestaShop
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 1.1
  */
  
class GraphXmlSwfCharts extends ModuleGraphEngine
{
	private	$_xml;
	private	$_values = NULL;
	private	$_legend = NULL;
	private	$_legend_more = '';
	private	$_titles = '';

	function __construct($type = null)
	{
		if ($type != null)
		{
			$this->_xml = '<chart>';
			parent::__construct($type);
		}
		else
		{
			$this->name = 'graphxmlswfcharts';
			$this->tab = 'Stats Engines';
			$this->version = 1.0;

			Module::__construct();
			
			$this->displayName = $this->l('XML/SWF Charts');
			$this->description = $this->l('XML/SWF Charts is a simple, yet powerful tool using Adobe Flash to create attractive web charts and graphs from dynamic data.');
		}
	}

	function install()
	{
		return (parent::install() AND $this->registerHook('GraphEngine'));
	}
	
	public static function hookGraphEngine($params, $drawer)
	{
		return '<script language="javascript">AC_FL_RunContent = 0;</script>
			<script language="javascript"> DetectFlashVer = 0; </script>
			<script src="../modules/graphxmlswfcharts/xml_swf_charts/AC_RunActiveContent.js" language="javascript"></script>
			<script language="JavaScript" type="text/javascript">
			<!--
			var requiredMajorVersion = 9;
			var requiredMinorVersion = 0;
			var requiredRevision = 45;
			-->
			</script>

			<script language="JavaScript" type="text/javascript">
			<!--
			if (AC_FL_RunContent == 0 || DetectFlashVer == 0) {
				alert("This page requires AC_RunActiveContent.js.");
			} else {
				var hasRightVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
				if(hasRightVersion) {
					AC_FL_RunContent(
					\'codebase\', \'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,45,0\',
					\'width\', \''.$params['width'].'\',
					\'height\', \''.$params['height'].'\',
					\'bgcolor\', \'#EFEFEF\',
					\'movie\', \'charts\',
					\'src\', \'../modules/graphxmlswfcharts/xml_swf_charts/charts\',
					\'FlashVars\', \'library_path=../modules/graphxmlswfcharts/xml_swf_charts/charts_library&xml_source='.urlencode($drawer).'\', 
					\'wmode\', \'opaque\',
					\'scale\', \'noScale\',
					\'id\', \'charts\',
					\'name\', \'charts\',
					\'menu\', \'true\',
					\'allowFullScreen\', \'true\',
					\'allowScriptAccess\',\'sameDomain\',
					\'quality\', \'high\',
					\'pluginspage\', \'http://www.macromedia.com/go/getflashplayer\',
					\'align\', \'middle\',
					\'play\', \'true\',
					\'devicefont\', \'false\',
					\'salign\', \'TL\'
					);
				} else {
					var alternateContent = \'This content requires the Adobe Flash Player. \'
					+ \'<u><a href=http://www.macromedia.com/go/getflash/>Get Flash</a></u>.\';
					document.write(alternateContent);
				}
			}
			// -->
			</script>
			<noscript>
				<P>This content requires JavaScript.</P>
			</noscript>';
	}

	private function drawColumn()
	{
		$this->_xml .= '<axis_category font="Geneva" bold="true" size="8" color="000000" />
			<axis_ticks value_ticks="true" position="inside" />
			<axis_value font="Geneva" bold="true" size="9" color="000000" show_min="true" />
			<chart_border color="000000" top_thickness="0" bottom_thickness="1" left_thickness="1" right_thickness="0" />
			<chart_transition type="scale" delay="0.5" duration="0.5" order="series" />
			<chart_label color="000000" size="12" position="cursor" background_color="E2EBEE" alpha="80" />
			<chart_guide horizontal="true" vertical="true" thickness="1" alpha="25" type="dashed" text_h_alpha="0" text_v_alpha="0" />';
	}

	private function drawLine()
	{
		$this->drawColumn();
		$this->_xml .= '<series_color><color>A3B6DA</color><color>C3413C</color><color>5A6C83</color><color>CA9A51</color><color>5B7751</color><color>55AA26</color><color>FF2398</color><color>427FC3</color></series_color>
			<chart_pref line_thickness="2" point_shape="none" fill_shape="false" />';
	}

	private function drawPie($counter)
	{
		$this->_xml .= '<chart_rect positive_color="ffffff" positive_alpha="20" negative_color="ff0000" negative_alpha="10" />
			<chart_label color="ffffff" alpha="90" font="Geneva" bold="true" size="8" position="inside" prefix="" suffix="" decimals="0" separator="" as_percentage="true" />
			<series_color>
				<color>427FC3</color>
				<color>C3413C</color>
				<color>5A6C83</color>
				<color>CA9A51</color>
				<color>5B7751</color>
				<color>55AA26</color>
				<color>FF2398</color>
			</series_color>
			<series_explode>';
		for ($i = 0; $i < $counter; $i++)
			$this->_xml .= '<number>9</number>';
		$this->_xml .= '</series_explode>';
		$this->_legend_more = ' bullet="circle"';
	}

	public function createValues($values)
	{
		$this->_values = $values;
		$this->_xml .= '<chart_type>'.$this->_type.'</chart_type><chart_grid_h alpha="20" color="000000" thickness="1" type="dashed" />';
		
		switch ($this->_type)
		{
			case 'pie':
				$this->drawPie(sizeof($values));
				break;
			case 'line':
				$this->drawLine();
				break;
			case 'column':
			default:
				$this->drawColumn();
				$this->_xml .= '<series_color><color>A3B6DA</color><color>C3413C</color><color>5A6C83</color><color>CA9A51</color><color>5B7751</color><color>55AA26</color><color>FF2398</color><color>427FC3</color></series_color>';
				break;
		}
	}

	public function setSize($width, $height)
	{
		if (isset($width) && !empty($width))
			$this->_width = $width;
		else
			$this->_width = 550;
		if (isset($height) && !empty($height))
			$this->_height = $height;
		else
			$this->_height = 270;
	}

	public function setLegend($legend)
	{
		$this->_legend = $legend;
		$this->_xml .= '<legend'.$this->_legend_more.' layout="horizontal" font="Geneva" bold="true" size="11" color="000000" alpha="85" shadow="low" transition="dissolve" delay="0.5" duration="0.25" fill_color="D4D4D4" fill_alpha="0" line_color="D4D4D4" line_alpha="0" line_thickness="0" />';
	}
	
	public function setTitles($titles)
	{
		$this->_titles = $titles;
		$this->_xml .= '<draw>';
		if (isset($titles['main']))
		{
			if ($this->_type == 'pie')
				$this->_xml .= '<text color="000033" alpha="50" font="Geneva" rotation="0" bold="true" x="0" y="0" width="'.$this->_width.'" height="'.$this->_height.'" h_align="center" v_align="top">'.$titles['main'].'</text>';
		}
		if ($this->_type != 'pie' AND isset($titles['x']) AND isset($titles['y']))
		{
			$this->_xml .= '<text color="000033" alpha="50" font="Geneva" rotation="0" bold="true" size="13" x="0" y="'.($this->_height - 30).'" width="'.$this->_width.'" height="30" h_align="right" v_align="middle">'.$titles['x'].'</text>';
			$this->_xml .= '<text color="000033" alpha="50" font="Geneva" rotation="-90" bold="true" size="13" x="0" y="'.$this->_height.'" width="'.$this->_height.'" height="30" h_align="center" v_align="middle">'.$titles['y'].'</text>';
		}
		$this->_xml .= '</draw>';
	}
	
	public function draw()
	{
		header('content-type: text/xml'); 

		$this->_xml .= '<chart_data><row><null/>';
		foreach ($this->_legend as $value)
			$this->_xml .= '<string>'.$value.'</string>';
		$this->_xml .= '</row>';
		
		if (!isset($this->_values[0]) || !is_array($this->_values[0]))
		{
			$this->_xml .= '<row><string>'.$this->_titles['main'].'</string>';
			foreach ($this->_values as $value)
				$this->_xml .= '<number>'. (($value > 0) ? $value : -$value) .'</number>'; //si jamais la valeur est n�gative... logiquement ne devrait jamais arriver
			$this->_xml .= '</row>';
		}
		else
		{
			$i = 1;
			foreach ($this->_values as $value)
			{
				$this->_xml .= '<row>';
				if (isset($this->_titles['main'][$i]))
					$this->_xml .= '<string>'.$this->_titles['main'][$i].'</string>';
				foreach ($value as $val)
					$this->_xml .= '<number>'.$val.'</number>';
				$this->_xml .= '</row>';
				$i++;
			}
		}
		$this->_xml .= '</chart_data>';
		$this->_xml .= '</chart>';
		echo $this->_xml;
	}
}
