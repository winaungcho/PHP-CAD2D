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
$model2d->setCanvas(220, 800, 1.5);
$model2d->drawGrid(-50, -50, 300, 600, 50);

$model2d->lineWidth(2);
$model2d->setColor(BLUE);

$p = array(
    'x' => 100,
    'y' => 200
);
$ent['center'] = $p;
$ent['rx'] = 80;
$ent['ry'] = 120;
$model2d->drawEllipse($ent);

$ent['ro'] = 45;
$ent['scale'] = 0.8;
$model2d->drawEllipse($ent);

$ent['start'] = 10;
$ent['end'] = 120;
$ent['rx'] = 120;
$ent['ry'] = 160;
$ent['scale'] = 1.0;
$ent['arrhead'] = true;
$ent['arrsize'] = 20;
$ent['ro'] = 0;
$model2d->drawEllipse($ent);

$ent['pat'] = DASHDOT;
$ent['ltscale'] = 5.0;
$ent['ro'] = 90;
$ent['scale'] = 1.5;
$model2d->drawEllipse($ent);


$ent['scale'] = 1.0;
$ent['pat'] = null;
$ent['ro'] = 0;
$ent['arrhead'] = false;
$ent['rx'] = 180;
$ent['ry'] = 40;
$ent['start'] = 0;
$ent['end'] = 360;

$model2d->drawEllipse($ent);

$ent['rx'] = 20;
$ent['ry'] = 40;
$ent['ro'] = -30;
$ent['color'] = GREEN;
$ent['edgeon'] = true;
$model2d->setColor(RED);
$model2d->fillEllipse($ent);

$fname = "cad2dellipse.png";
imagePng($model2d->canvas, './images/'.$fname);
imagedestroy($model2d->canvas);

echo "<img src='images/$fname?u=".time()."'/>";
?>
