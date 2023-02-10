<?php
/******
 * CAD2D
 *
 * [CAD2D] class create 2 dimensional engineering drawing.
 * Entity Data are strore in the associative array.
 * Class draw the drawing on image after entity data.
 * This class is free for the educational use as long as maintain this header together with this class.
 * Author: Win Aung Cho
 * Contact winaungcho@gmail.com
 * version 1.0
 * Date: 10-02-2023
 *
 ******/
class CAD2D
{
    var $width = 800;
    var $height = 1000;
    var $X0;
    var $Y0;
    var $scale = 1;

    var $canvas = null;
    var $defcolor = 0;
    var $deflinew = 1;

    public function __construct($w=800, $h=1000, $s=1.5)
    {
    	$this->width  = $w;
    	$this->height = $h;
    	$this->scale = $s;
        $this->X0 = $this->width / 8;
        $this->Y0 = $this->height * 3 / 4;
        
        $this->canvas = imagecreatetruecolor($this->width, $this->height);
        $this->defcolor = imagecolorallocate($this->canvas, 255, 255, 255);

        define(BLACK, imagecolorallocate($this->canvas, 0, 0, 0));
        define(DKGREY, imagecolorallocate($this->canvas, 80, 80, 80));
        define(GREY, imagecolorallocate($this->canvas, 128, 128, 128));
        define(LTGREY, imagecolorallocate($this->canvas, 220, 220, 220));
        define(WHITE, imagecolorallocate($this->canvas, 255, 255, 255));
        define(RED, imagecolorallocate($this->canvas, 255, 0, 0));
        define(GREEN, imagecolorallocate($this->canvas, 0, 255, 0));
        define(BLUE, imagecolorallocate($this->canvas, 0, 200, 250));
        define(ORANGE, imagecolorallocate($this->canvas, 255, 200, 0));
        define(BROWN, imagecolorallocate($this->canvas, 220, 110, 0));
        define(PI, 3.1415);
        define(DASH, $this->patternarr(3,1,3,0));
        define(DASHDOT, $this->patternarr(6,1,2,0,2,1,2,0));
        define(CENTER, $this->patternarr(6,1,2,0,2,1,2,0, 3,1,3,0));
        $this->dimstyle = array(
        	"color" => ORANGE,
        	"extlen" => 20,
        	"tcolor" => GREEN,
        	"tsize" => 20,
        	"arrsize" => 10
        );
        $this->textstyle = array(
        	"hor" => "center",
        	"ver" => "bottom",
        	"shadow" => false,
        	"border" => false,
        	"shadowcol" => GREY,
        	"bordercol" => GREEN
        );
    }
    public function setCanvas($x, $y, $s)
    {
    	$this->X0 = $x;
    	$this->Y0 = $y;
    	$this->scale = $s;
    }
    protected function real2draw($p)
    {
        $x = $this->scale * $p['x'] + $this->X0;
        $y = - $this->scale * $p['y'] + $this->Y0;
        return array(
            "x" => $x,
            "y" => $y
        );
    }
    public function drawPoint($ent)
    {
        $size = ($ent["size"] ?? 10) * $this->scale;
        $type = $ent['type'] ?? 1;
        $v1 = $this->real2draw($ent["p1"]);
        imagesetthickness($this->canvas, $this->deflinew);
        if ($type == 0) imagesetpixel($this->canvas, $v1['x'], $v1['y'], $this->defcolor);
        if ($type & 1)
        {
            imageline($this->canvas, $v1['x'] - $size, $v1['y'], $v1['x'] + $size, $v1['y'], $this->defcolor);
            imageline($this->canvas, $v1['x'], $v1['y'] - $size, $v1['x'], $v1['y'] + $size, $this->defcolor);
        }
        if ($type & 2)
        {
            imagearc($this->canvas, $v1['x'], $v1['y'], $size, $size, 0, 360, $this->defcolor);
        }
        if ($type & 4)
        {
        	$size = $size/2;
            imageline($this->canvas, $v1['x'] - $size, $v1['y'] - $size, $v1['x'] + $size, $v1['y'] - $size, $this->defcolor);
            imageline($this->canvas, $v1['x'] - $size, $v1['y'] - $size, $v1['x'] - $size, $v1['y'] + $size, $this->defcolor);
            imageline($this->canvas, $v1['x'] - $size, $v1['y'] + $size, $v1['x'] + $size, $v1['y'] + $size, $this->defcolor);
            imageline($this->canvas, $v1['x'] + $size, $v1['y'] - $size, $v1['x'] + $size, $v1['y'] + $size, $this->defcolor);
        }
    }
    public function drawLine($ent)
    {
        $p1 = $ent["p1"];
        $p2 = $ent["p2"];
        $v1 = $this->real2draw($p1);
        $v2 = $this->real2draw($p2);
        imagesetpixel($this->canvas, $v1['x'], $v1['y'], $this->defcolor);
        imagesetpixel($this->canvas, $v2['x'], $v2['y'], $this->defcolor);
        
        $ltscale = $ent['ltscale'] ?? 1.0;
        if (isset($ent["off1"]) && $ent["off1"] != 0) $p1 = $this->lineextPoint($p2, $p1, -$ent["off1"]);
        if (isset($ent["off2"]) && $ent["off2"] != 0) $p2 = $this->lineextPoint($p1, $p2, $ent["off2"]);
        $v1 = $this->real2draw($p1);
        $v2 = $this->real2draw($p2);

        $p['x'] = ($ent["p1"]['x'] + $ent["p2"]['x']) / 2;
        $p['y'] = ($ent["p1"]['y'] + $ent["p2"]['y']) / 2;
        
        imagesetthickness($this->canvas, $this->deflinew);
        $pat = $ent['pat'] ?? null;
        $this->imgLine($v1['x'], $v1['y'], $v2['x'], $v2['y'], $pat, $ltscale);
    }
    public function drawRect($ent)
    {
    	$w = $ent['width'] ?? 2.0;
    	$p1 = $ent["p1"];
        $p2 = $ent["p2"];
        if (isset($ent["off1"]) && $ent["off1"] != 0) $p1 = $this->lineextPoint($p2, $p1, -$ent["off1"]);
        if (isset($ent["off2"]) && $ent["off2"] != 0) $p2 = $this->lineextPoint($p1, $p2, $ent["off2"]);
    	$pr1 = $this->lineperpPoint($p1, $p2, -$w/2, 0.0);
    	$pr2 = $this->lineperpPoint($p1, $p2, -$w/2, 1.0);
    	$pr3 = $this->lineperpPoint($p1, $p2, $w/2, 1.0);
    	$pr4 = $this->lineperpPoint($p1, $p2, $w/2, 0.0);
        $v1 = $this->real2draw($pr1);
        $v2 = $this->real2draw($pr2);
        $v3 = $this->real2draw($pr3);
        $v4 = $this->real2draw($pr4);

        $p['x'] = ($ent["p1"]['x'] + $ent["p2"]['x']) / 2;
        $p['y'] = ($ent["p1"]['y'] + $ent["p2"]['y']) / 2;

        imagesetthickness($this->canvas, $this->deflinew);
        imageline($this->canvas, $v1['x'], $v1['y'], $v2['x'], $v2['y'], $this->defcolor);
        imageline($this->canvas, $v2['x'], $v2['y'], $v3['x'], $v3['y'], $this->defcolor);
        imageline($this->canvas, $v3['x'], $v3['y'], $v4['x'], $v4['y'], $this->defcolor);
        imageline($this->canvas, $v4['x'], $v4['y'], $v1['x'], $v1['y'], $this->defcolor);
    }
    
    public function drawEllipse($ent)
    {
        $arrhead = $ent['arrhead'] ?? false;
        $arrsize = $ent['arrsize'] ?? 20;

        $scale = $ent['scale'] ?? 1.0;
        $ltscale = $ent['ltscale'] ?? 1.0;
        $a = deg2rad($ent['ro'] ?? 0);
        $c = $ent["center"];
        $rx = $ent["rx"];
        $ry = $ent["ry"];
        $start = deg2rad($ent['start'] ?? 0);
        $end = deg2rad($ent['end'] ?? 360);
        $res = 4.0 / max($rx, $ry);
        $res = PI/24;
        $v1 = null;
        $v2 = null;
        imagesetthickness($this->canvas, $this->deflinew);
        $pat = $ent['pat'] ?? null;
        for ($t = $start;$t <= $end;$t += $res)
        {
            $x = $rx*cos($t);
            $y = $ry*sin($t);
            $p = array(
                'x' => $x,
                'y' => $y
            );
            $p = $this->vecAdd($c, $this->rotatePoint($this->scalePoint($p, $scale) , $a));

            $v2 = $this->real2draw($p);
            if ($v1 != null)
            {
                $this->imgLine( $v1['x'], $v1['y'], $v2['x'], $v2['y'], $pat, $ltscale);
                if (($t + $res) > $end && $arrhead) $this->imgArrowHead($v1['x'], $v1['y'], $v2['x'], $v2['y'], $arrsize * $scale * $this->scale);
            }
            $v1 = $v2;
        }
    }
    public function drawPoly($ent)
    {
        $lines = $ent['points'];
        $base = $ent['base'];
        $scale = $ent['scale'] ?? 1.0;
        $ltscale = $ent['ltscale'] ?? 1.0;
        $arrhead = $ent['arrhead'] ?? false;
        $arrsize = $ent['arrsize'] ?? 20;
        $close = $ent['close'] ?? false;
        $a = deg2rad($ent['ro'] ?? 0);
        $n = count($lines);
        $p = $this->vecAdd($base, $this->rotatePoint($this->scalePoint($lines[0], $scale) , $a));

        $v1 = $this->real2draw($p);
        $v2 = array();
        $start = $v1;
        imagesetthickness($this->canvas, $this->deflinew);
        $pat = $ent['pat'] ?? null;
        
        for ($i = 1;$i < $n;$i++)
        {
            $p = $this->vecAdd($base, $this->rotatePoint($this->scalePoint($lines[$i], $scale) , $a));
            $v2 = $this->real2draw($p);
            $this->imgLine($v1['x'], $v1['y'], $v2['x'], $v2['y'], $pat, $ltscale);
            if ($i == $n - 1 && $arrhead) $this->imgArrowHead($v1['x'], $v1['y'], $v2['x'], $v2['y'], $arrsize * $scale * $this->scale);
            $v1 = $v2;
        }
        $v1 = $start;
        if ($close) $this->imgLine($v1['x'], $v1['y'], $v2['x'], $v2['y'], $pat, $ltscale);
    }
    public function drawDimAllign($ent)
    {
    	$oldc = $this->setColor($this->dimstyle["color"]);
    	$oldw = $this->lineWidth(1);
        $dist = $ent['dist'] ?? 60;
        $ps = $this->lineperpPoint($ent['p1'], $ent['p2'], $dist, 0.0);
        $pm = $this->lineperpPoint($ent['p1'], $ent['p2'], $dist, 0.5);
        $pe = $this->lineperpPoint($ent['p1'], $ent['p2'], $dist, 1.0);

        $dent['p1'] = $ps;
        $dent['type'] = 2;
        $dent['size'] = $this->dimstyle["arrsize"];
        $this->drawPoint($dent);
        $dent['p1'] = $pe;
        $dent['type'] = 2;
        $this->drawPoint($dent);

        $dent['p1'] = $ent["p1"];
        $dent['type'] = 0;
        $this->drawPoint($dent);

        $dent['p1'] = $ent["p2"];
        $dent['type'] = 0;
        $this->drawPoint($dent);

        $ext = $this->dimstyle["extlen"];
        $lent = array(
            'p1' => $ent["p1"],
            'p2' => $ps,
            'ro' => 0
        );
        $lent['off1'] = $ext / 2;
        $lent['off2'] = $ext;
        $this->drawLine($lent);

        $lent['p2'] = $pe;
        $lent['p1'] = $ent["p2"];
        $this->drawLine($lent);

        $lent['p1'] = $ps;
        $lent['p2'] = $pe;
        $lent['off1'] = - $ext;
        $lent['off2'] = $ext;
        $this->drawLine($lent);

        $dir = $this->lineDir($ent["p1"], $ent["p2"]);
        $ang = $dir['t'];
        $text = sprintf("%.2f", $dir['r']);
        $text = $ent['text'] ?? $text;
        $info = array(
            "p" => $pm,
            "text" => $text,
            "color" => $this->dimstyle["tcolor"],

            "size" => $this->dimstyle["tsize"],
            "ro" => $ang,
            "hor" => "center",
            "ver" => "bottom"
        );
        $this->drawTTFText($info);
        $this->setColor($oldc);
        $this->lineWidth($oldw);
    }

    public function drawTTFText($info)
    {
        $default = array(
            "font" => '../../ttf/mm3.ttf',
            "size" => 20,
            "scale" => 1.0,
            "ro" => 0
        );
        extract($this->textstyle);
        extract($default);
        extract($info);

        $v = $this->real2draw($p);
        $x = $v['x'];
        $y = $v['y'];
        $scale = $info['scale'] ?? 1.0;
        $size *= $this->scale*$scale;
        $box = imagettfbbox($size, $ro, $font, $text);
        // left bottom
        if ($hor == "left" && $ver == "bottom")
        {
            $x -= $box[0];
            $y -= $box[1];
        }
        if ($hor == "right" && $ver == "bottom")
        {
            $x -= $box[2];
            $y -= $box[3];
        }
        if ($hor == "left" && $ver == "top")
        {
            $x -= $box[6];
            $y -= $box[7];
        }
        if ($hor == "right" && $ver == "top")
        {
            $x -= $box[4];
            $y -= $box[5];
        }
        if ($hor == "" && $ver == "")
        {
            $x += $box[0];
            $y += $box[1];
        }
        if ($hor == "center" && $ver == "bottom")
        {
            $x -= ($box[0] + $box[2]) / 2;
            $y -= ($box[1] + $box[3]) / 2;
        }
        if ($hor == "center" && $ver == "top")
        {
            $x -= ($box[6] + $box[4]) / 2;
            $y -= ($box[7] + $box[5]) / 2;
        }
        if ($hor == "left" && $ver == "middle")
        {
            $x -= ($box[0] + $box[6]) / 2;
            $y -= ($box[1] + $box[7]) / 2;
        }
        if ($hor == "right" && $ver == "middle")
        {
            $x -= ($box[2] + $box[4]) / 2;
            $y -= ($box[3] + $box[5]) / 2;
        }

        if ($hor == "center" && $ver == "middle")
        {
            $x -= ($box[0] + $box[2] + $box[4] + $box[6]) / 4;
            $y -= ($box[1] + $box[3] + $box[5] + $box[7]) / 4;
        }
        if ($border)
        {
            imageline($this->canvas, $box[0] + $x, $box[1] + $y, $box[2] + $x, $box[3] + $y, $bordercol);
            imageline($this->canvas, $box[2] + $x, $box[3] + $y, $box[4] + $x, $box[5] + $y, $bordercol);
            imageline($this->canvas, $box[4] + $x, $box[5] + $y, $box[6] + $x, $box[7] + $y, $bordercol);
            imageline($this->canvas, $box[6] + $x, $box[7] + $y, $box[0] + $x, $box[1] + $y, $bordercol);
        }
        $v1 = $this->real2draw($p);
        if ($shadow) imagettftext($this->canvas, $size, $ro, $x + 2, $y + 2, $shadowcol, $font, $text);
        imagettftext($this->canvas, $size, $ro, $x, $y, $color, $font, $text);
        return array("x" => $x, "y" => $y);
    }

    public function drawArrow($ent)
    {
        $arrsize = $ent['arrsize'] ?? 20;
        $ltscale = $ent['ltscale'] ?? 1.0;
        $lines = $ent['points'];
        $n = count($lines);
        $v1 = $this->real2draw($lines[0]);
        $v2 = array();
        $p = $lines[0];
        imagesetthickness($this->canvas, $this->deflinew);
        $pat = $ent['pat'] ?? null;
        for ($i = 1;$i < $n;$i++)
        {
            $p = $this->vecAdd($p, $lines[$i]);
            $v2 = $this->real2draw($p);
            $this->imgLine($v1['x'], $v1['y'], $v2['x'], $v2['y'], $pat, $ltscale);
            if ($i == $n - 1) $this->imgArrowHead($v1['x'], $v1['y'], $v2['x'], $v2['y'], $arrsize * $this->scale);
            $v1 = $v2;
        }
    }
    public function drawGrid($x1, $y1, $x2, $y2, $spc)
    {
    	$lw = $this->lineWidth(1);
    	$c = $this->setColor(GREY);
    	$gent['p1'] = [x => $x1, y => $y1];
    	$gent['p2'] = [x => $x1, y => $y2];
    	
    	for ($i = $x1;$i <= $x2;$i += $spc)
    	{
    		if ($gent['p1']['x'] == 0)
    			$this->setColor(GREY);
    		else $this->setColor(DKGREY);
        	$this->drawLine($gent);
        	$gent['p1']['x'] += $spc;
        	$gent['p2']['x'] += $spc;
    	}

    	$gent['p1'] = [x => $x1, y => $y1];
    	$gent['p2'] = [x => $x2, y => $y1];
    	for ($i = $y1;$i <= $y2;$i += $spc)
    	{
    		if ($gent['p1']['y'] == 0)
    			$this->setColor(GREY);
    		else $this->setColor(DKGREY);
        	$this->drawLine($gent);
        	$gent['p1']['y'] += $spc;
        	$gent['p2']['y'] += $spc;
    	}
    	$this->lineWidth($lw);
    	$this->setColor($c);
	}
	
    public function fillPoly($ent)
    {
    	$img = null;
    	$color = $ent['color'] ?? IMG_COLOR_TILED;
    	if (!$ent['color']){
    		$hstyle = $ent['hstyle'] ?? "hatch";
    		$hcolor = $ent['hcolor'] ?? WHITE;
    		$hscale = $ent['hscale'] ?? 1.0;
    		$img = $this->setHatch($hstyle, $hcolor, $hscale);
    	}
        $lines = $ent['points'];
        $base = $ent['base'];
        
        $edgeon = $ent['edgeon'] ?? false;
        $scale = $ent['scale'] ?? 1.0;
        $a = deg2rad($ent['ro'] ?? 0);
        $n = count($lines);
        $points = array();
        for ($i = 0;$i < $n;$i++)
        {
            $p = $this->vecAdd($base, $this->rotatePoint($this->scalePoint($lines[$i], $scale) , $a));
            $v = $this->real2draw($p);
            $points[] = $v['x'];
            $points[] = $v['y'];
        }
        
        imagefilledpolygon($this->canvas, $points, $n, $color);
        
        if ($edgeon){
        	imagesetthickness($this->canvas, $this->deflinew);
        	imagepolygon($this->canvas, $points, $n, $this->defcolor);
        }
        
    }
    public function fillEllipse($ent)
    {
    	$eent = $ent;
    	$res = PI/24;
    	$eent['base'] = $ent['center'];
    	
        $rx = $ent["rx"];
        $ry = $ent["ry"];
    	$points = [];
    	for ($t = 0;$t <= 2*PI;$t += $res)
        {
            $x = $rx*cos($t);
            $y = $ry*sin($t);
            $p = array(
                'x' => $x,
                'y' => $y
            );
            $points[] = $p;
        }
        $eent['points'] = $points;
        $this->fillPoly($eent);
        
    }
    public function setColor($c)
    {
    	$old = $this->defcolor;
        $this->defcolor = $c;
        return $old;
    }
    public function lineWidth($w)
    {
    	$old = $this->deflinew;
        $this->deflinew = $w;
        return $old;
    }
    public function updateDimStyle($data)
    {
    	$this->updatearr($this->dimstyle, $data);
    }
    public function updateTextStyle($data)
    {
    	$this->updatearr($this->textstyle, $data);
    }
    protected function setHatch($hstyle, $hcolor, $scale)
	{
		$img = imagecreatetruecolor(32, 32);
    		switch ($hstyle){
    			case "brick":
    			imageline($img, 0,15, 15, 0, $hcolor);
    			imageline($img, 0,18, 18, 0, $hcolor);
    			imageline($img, 16,31, 31, 16, $hcolor);
    			imageline($img, 19,31, 31, 19, $hcolor);
    			break;
    			case "dblhatch":
    			imageline($img, 16, 0, 31, 15, $hcolor);
    			imageline($img, 0, 0, 31, 31, $hcolor);
    			imageline($img, 0, 16, 15, 31, $hcolor);
    			case "hatch":
    			imageline($img, 0,15, 15, 0, $hcolor);
    			imageline($img, 0,31, 31, 0, $hcolor);
    			imageline($img, 16, 31, 31, 16, $hcolor);
    			break;
    			case "dblstrip":
    			imageline($img, 15, 0, 15, 31, $hcolor);
    			imageline($img, 31, 0, 31, 31, $hcolor);
    			case "strip":
    			imageline($img, 0,15, 31, 15, $hcolor);
    			imageline($img, 0,31, 31, 31, $hcolor);
    			break;
    			
    		}
    		$img = $this->scaleImage($img, $this->scale*$scale);
    		imagesettile($this->canvas, $img);
    	return $img;
	}
    protected function lineextPoint($p1, $p2, $dist)
    {
        $dx = $p2["x"] - $p1["x"];
        $dy = $p2["y"] - $p1["y"];
        $dl = sqrt($dx * $dx + $dy * $dy);
        $d = $dist;
        $pi = array(
            "x" => 0,
            "y" => 0
        );
        $pi = array(
            "x" => $p2["x"] + $dx / $dl * $d,
            "y" => $p2["y"] + $dy / $dl * $d
        );
        return $pi;
    }
    protected function lineperpPoint($p1, $p2, $dist, $loc = 0.5)
    {
        $dx = $p2["x"] - $p1["x"];
        $dy = $p2["y"] - $p1["y"];
        $dl = sqrt($dx * $dx + $dy * $dy);
        $pi = array(
            "x" => $dx * $loc,
            "y" => $dy * $loc
        );
        $pi = $this->vecAdd($p1, $pi);
        $p = array(
            "x" => - $dy / $dl * $dist,
            "y" => $dx / $dl * $dist
        );
        $p = $this->vecAdd($p, $pi);
        return $p;
    }
    protected function lineDir($p1, $p2)
    {
        $dx = $p2["x"] - $p1["x"];
        $dy = $p2["y"] - $p1["y"];
        $dl = sqrt($dx * $dx + $dy * $dy);
        $t = rad2deg(atan2($dy, $dx));
        return array(
            "r" => $dl,
            "t" => $t
        );
    }
    protected function vecAdd($v1, $v2)
    {
        $p['x'] = $v1['x'] + $v2['x'];
        $p['y'] = $v1['y'] + $v2['y'];
        return $p;
    }

    protected function rotatePoint($v, $angle)
    {
        $p['x'] = cos($angle) * $v['x'] - sin($angle) * $v['y'];
        $p['y'] = sin($angle) * $v['x'] + cos($angle) * $v['y'];
        return $p;
    }
    protected function scalePoint($v, $scale)
    {
        $p['x'] = $scale * $v['x'];
        $p['y'] = $scale * $v['y'];
        return $p;
    }
    public function avgPoints(){
    	
    	$cnt = func_num_args();
        $pts = func_get_args();
        $sumx = 0; $sumy = 0;
        for ($i = 0; $i<$cnt; $i++){
        	$sumx += $pts[$i][x];
        	$sumy += $pts[$i][y];
        }
       
    	return [x => $sumx/$cnt, y => $sumy/$cnt];
    } 
    
    protected function imgLine($x1, $y1, $x2, $y2, $pat, $scale){
    	$pat2 = array(array("len" => 6, "m" => 1), array("len" => 2, "m" => 0),
    		array("len" => 2, "m" => 1), array("len" => 2, "m" => 0));
    	if ($pat){
    		$scale = $scale ?? 1.0;
    		$values = $this->pattern2stylearr($pat, $this->defcolor, $this->scale * $scale);
    		imagesetstyle($this->canvas, $values); 
    		imageline($this->canvas, $x1, $y1, $x2, $y2, IMG_COLOR_STYLED); 
    	} else imageline($this->canvas, $x1, $y1, $x2, $y2, $this->defcolor); 
    }
    public function patternarr(){
    	$arr = array(array("len" => 6, "m" => 1), array("len" => 2, "m" => 0),
    		array("len" => 2, "m" => 1), array("len" => 2, "m" => 0));
    	$cnt = func_num_args();
        $args = func_get_args();
        $arr = [];
        for ($i = 0; $i<$cnt; $i+=2){
        	$arr[] = array("len" => $args[$i], "m" => $args[$i+1]);
        }
       
    	return $arr;
    }
    protected function pattern2stylearr($pat, $color, $scale){
		$arr = [];
		$n = count($pat);
		$k = 0;
		for ($j=0; $j < $n; $j++){
			for ($i=0; $i < ($pat[$j]['len']*$scale); $i++){
				if ($pat[$j]['m'] == 1)
					$arr[$k] = $color;
				else $arr[$k] = IMG_COLOR_TRANSPARENT;
				$k++;
			}
		}
		return $arr;
	}
	protected function updatearr(&$arr, $data){
		foreach($data as $k=>$v){
			$arr[$k] = $v;
        }
        return $arr;
	}
    protected function imgArrowHead($tailX, $tailY, $tipX, $tipY, $size = 50)
    {
        $ang = deg2rad(25);
        $arrowLength = $size; //can be adjusted
        $dx = $tipX - $tailX;
        $dy = $tipY - $tailY;

        $theta = atan2($dy, $dx);

        $phi = $ang; //35 angle, can be adjusted
        $x = $tipX - $arrowLength * cos($theta + $phi);
        $y = $tipY - $arrowLength * sin($theta + $phi);

        $phi2 = - $ang; //-35 angle, can be adjusted
        $x2 = $tipX - $arrowLength * cos($theta + $phi2);
        $y2 = $tipY - $arrowLength * sin($theta + $phi2);

        $points = array();
        $points[] = $tipX;
        $points[] = $tipY;
        $points[] = $x;
        $points[] = $y;
        $points[] = $x2;
        $points[] = $y2;

        imagepolygon($this->canvas, $points, 3, $this->defcolor);
    }
	protected function scaleImage($image, $s)
	{
		$oldw = imagesx($image);
		$oldh = imagesy($image);
		$w = floor($oldw*$s);
		$h = floor($oldh*$s);
		$temp = imagecreatetruecolor($w, $h);
		imagecopyresampled($temp, $image, 0, 0, 0, 0, $w, $h, $oldw, $oldh);
		return $temp;
	}
}

?>
