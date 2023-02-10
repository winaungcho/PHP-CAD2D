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
$ent = array(
    'p1' => array(
        'x' => 0,
        'y' => 0
    ) ,
    'p2' => array(
        'x' => 200,
        'y' => 400
    )
);
$model2d->lineWidth(2);
$model2d->setColor(RED);
$ent['width'] = 50;
$model2d->drawRect($ent);

$model2d->setColor(GREEN);
$dent['p1'] = $ent[p1];
$dent['type'] = 1;
$dent['size'] = 10;
$model2d->drawPoint($dent);

$dent['p1'] = $ent[p2];
$dent['type'] = 2;
$model2d->drawPoint($dent);

$ent[p1][x] += 100;
$ent[p2][x] += 100;

$model2d->setColor(RED);
$ent['width'] = 80;
$ent[off1] = 50;
$ent[off2] = -50;
$model2d->drawRect($ent);

$model2d->setColor(GREEN);
$dent['p1'] = $ent[p1];
$dent['type'] = 3;
$dent['size'] = 10;
$model2d->drawPoint($dent);

$dent['p1'] = $ent[p2];
$dent['type'] = 4;
$model2d->drawPoint($dent);

$pm = $model2d->avgPoints($ent[p1], $ent[p2]);
$dent['p1'] = $pm;
$dent['type'] = 5;
$model2d->drawPoint($dent);

$fname = "cad2drectpt.png";
imagePng($model2d->canvas, './images/'.$fname);
imagedestroy($model2d->canvas);

echo "<img src='images/$fname?u=".time()."'/>";
?>
