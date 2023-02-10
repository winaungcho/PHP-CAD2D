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
require_once ("../src/cad2d.php");




$model2d = new CAD2D();
$model2d->setCanvas(200, 800, 1.5);
$model2d->drawGrid(-50, -50, 300, 600, 50);

$p1 = ['x' => 50, 'y' => 0];
$p2 = ['x' => 250, 'y' => 0];
$p3 = ['x' => 150, 'y' => 200];
$ent = [
    'p1' => $p1,
    'p2' => $p2
];
$model2d->lineWidth(2);
$model2d->drawLine($ent);

$ent = [
    'p1' => $p3,
    'p2' => $p2
];
$model2d->drawLine($ent);

$ent = [
    'p1' => $p1,
    'p2' => $p3
];
$model2d->drawLine($ent);


$info = array(
    "p" => $p1,
    "text" => "Trust",
    "color" => BLUE,

    "size" => 20,
    "scale" => 2.0,
    "ro" => 0
);
$tstyle = array(
	"hor" => "right",
    "ver" => "top",
    "shadow" => true,
    "border" => true,
    "shadowcol" => GREY,
    "bordercol" => GREEN
);
$model2d->updateTextStyle($tstyle);
$model2d->drawTTFText($info);

$tstyle[hor] = "left";
$tstyle[ver] = "top";
$info[p] = $p2;
$info[text] = "Love";
$model2d->updateTextStyle($tstyle);
$model2d->drawTTFText($info);

$tstyle[hor] = "center";
$tstyle[ver] = "bottom";
$tstyle[border] = false;
$info[p] = $p3;
$info[text] = "Respect";
$info[scale] = 1.0;
$model2d->updateTextStyle($tstyle);
$model2d->drawTTFText($info);

$p1[y] += 200;
$p2[y] += 200;
$p3[y] += 100;
$info[p] = $p1;
$info[text] = "Trust";
$tstyle[hor] = "left";
$tstyle[ver] = "bottom";
$info[ro] = 60;
$model2d->updateTextStyle($tstyle);
$model2d->drawTTFText($info);

$info[p] = $p3;
$info[text] = "Respect";
$tstyle[hor] = "center";
$tstyle[ver] = "bottom";
$info[ro] = 0;
$model2d->updateTextStyle($tstyle);
$model2d->drawTTFText($info);

$info[p] = $p2;
$info[text] = "Love";
$tstyle[hor] = "right";
$tstyle[ver] = "bottom";
$info[ro] = -60;
$model2d->updateTextStyle($tstyle);
$model2d->drawTTFText($info);

$fname = "cad2dtext.png";
imagePng($model2d->canvas, './images/'.$fname);
imagedestroy($model2d->canvas);

echo "<img src='images/$fname?u=".time()."'/>";
?>
