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

// basic polyline entity
$ent['points'] = array(
    array(
        'x' => 0,
        'y' => 0
    ) ,
    array(
        'x' => 80,
        'y' => 0
    ) ,
    array(
        'x' => 100,
        'y' => 75
    ) ,
    array(
        'x' => 80,
        'y' => 150
    ) ,
    array(
        'x' => 0,
        'y' => 150
    )
);
$ent['base'] = array(
    'x' => 100,
    'y' => 200
);
$ent['arrhead'] = true;
$model2d->lineWidth(2);
$model2d->setColor(BLUE);
$model2d->drawPoly($ent);
$ent['ro'] = 45;
$model2d->drawPoly($ent);
$ent['ro'] = 90;
$ent['scale'] = 1.2;
$ent['close'] = true;
$ent['arrhead'] = false;
$ent['pat'] = DASHDOT;
$ent['ltscale'] = 4.0;
$model2d->drawPoly($ent);

$ent['scale'] = 1.4;
$ent['pat'] = null;
$ent['ro'] = 135;
$model2d->setColor(GREEN);
$model2d->drawPoly($ent);

$ent['scale'] = 1.6;
$ent['ro'] = 190;
$model2d->setColor(ORANGE);
$model2d->lineWidth(6);
$model2d->drawPoly($ent);

$ent['ro'] = 90;
$ent['scale'] = 0.5;
//$ent['color'] = LTGREY;
$ent['base'] = ['x' => 75,'y' => 400];
$model2d->fillPoly($ent);

$ent['edgeon'] = true;
$ent['base']['x'] += 100;
$ent['hstyle'] = "dblhatch";
$model2d->fillPoly($ent);

$ent['base']['x'] += 100;
$ent['hstyle'] = "strip";
$model2d->fillPoly($ent);

$ent['base']['x'] += 100;
$ent['hstyle'] = "dblstrip";
$ent['hcolor'] = GREEN;
$ent['hscale'] = 0.5;
$model2d->fillPoly($ent);

$ent['base']['y'] -= 100;
$ent['hstyle'] = "brick";
$model2d->fillPoly($ent);

$fname = "cadpoly.png";
imagePng($model2d->canvas, './images/'.$fname);
imagedestroy($model2d->canvas);

echo "<img src='images/$fname?u=".time()."'/>";
?>
