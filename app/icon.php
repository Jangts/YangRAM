<?php
header(sprintf("Content-Type: %s;charset=%s", 'image/svg+xml', 'UTF-8'));
if (isset($_SERVER["HTTP_IF_MODIFIED_SINCE"])||isset($_SERVER['HTTP_IF_UNMODIFIED_SINCE'])){
	header('HTTP/1.1 304 Not Modified');
	exit;
}
header('Cache-Control: public');
header('Cache-Control: max-age=3153600000');
header('Expires: ' . preg_replace('/.{5}$/', 'GMT', gmdate('r', intval(time() + 3153600000))));
header('Last-Modified: ' . gmdate("D, d M Y H:i:s", time()).' GMT');
if(isset($_GET['s'])&&preg_match('/^\d+$/', $_GET['s'])&&$_GET['s']>0){
	$size = intval($_GET['s']);
}else{
	$size = 180;
}
$width = $size.'px';
$height = $size.'px';
$opacity = 1;
if(isset($_GET['o'])){
	$int = intval($_GET['o']);
	if($int>0&&$int<=100){
		$opacity = $int /100;
	}else{
		$opacity = 0.1;
	}
}
if(isset($_GET['c'])){
	$colors = array('#FDBB11','#BE1E2D','#EF5125','#D41C53','#7FBA42','#CCCCCC','#33A5DD');
	switch($_GET['c']){
		case '0':
		case 'black':
		$yl = $rd = $na = $ma = $gn	= $as = $az = '#000000';
		break;
		case 'yellow':
		$yl = $rd = $na = $ma = $gn	= $as = $az = $colors[0];
		break;
		case 'red':
		$yl = $rd = $na = $ma = $gn	= $as = $az = $colors[1];
		break;
		case 'nacarat':
		$yl = $rd = $na = $ma = $gn	= $as = $az = $colors[2];
		break;
		case 'magenta':
		$yl = $rd = $na = $ma = $gn	= $as = $az = $colors[3];
		break;
		case 'green':
		$yl = $rd = $na = $ma = $gn	= $as = $az = $colors[4];
		break;
		case 'azure':
		$yl = $rd = $na = $ma = $gn	= $as = $az = $colors[6];
		break;
		default:
		list($yl,$rd,$na,$ma,$gn,$as,$az) = $colors;
		break;
	}
}else{
	$yl = $rd = $na = $ma = $gn	= $as = $az = '#FFFFFF';
}
echo <<<SVG
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"> 
<svg version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="$width" height="$height" opacity="$opacity" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
<g>
  <rect x="263.619" y="61.47" transform="matrix(-0.9239 -0.3826 0.3826 -0.9239 682.3155 516.4408)" fill="$yl" width="257.794" height="257.793"/>
  <polygon fill="$rd" points="-310.24,36.512 198.162,247.082 301.866,-3.304 408.731,-261.322"/>
  <polygon fill="$na" points="553.665,88.549 424.407,-223.48 333.021,-2.838"/>
  <polygon fill="$ma" points="-11.255,758.262 72.87,555.15 189.045,274.654 -294.564,74.353"/>
  <polygon fill="$gn" points="122.452,505.565 434.481,376.308 213.836,284.924 122.451,505.566"/>
  <polygon fill="$as" points="722.238,495.489 722.24,495.489 578.458,148.401 375.149,639.271"/>
  <polygon fill="$az" points="444.751,401.098 107.931,540.626 107.929,540.627 3.269,793.322 340.092,653.793 444.753,401.098"/>
</g>
</svg>
SVG;
