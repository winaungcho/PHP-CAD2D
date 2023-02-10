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
$model2d->drawGrid(-50, -50, 300, 600, 50);
// basic line entity
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
$model2d->setColor(BLUE);
$model2d->drawLine($ent);

// line type
$ent['p1']['y'] += 50;
$ent['p2']['y'] += 50;
$ent['pat'] = DASHDOT;
$ent['ltscale'] = 5.0;
$model2d->setColor(RED);
$model2d->drawLine($ent);

// line with offset
$ent['pat'] = null;
$ent['p1']['y'] += 50;
$ent['p2']['y'] += 50;
$ent['off1'] = 50;
$ent['off2'] = - 50;
$model2d->setColor(GREEN);
$model2d->drawLine($ent);

// line width
$model2d->setColor(BLUE);
$ent = [];
$ent['p1'] = [x => 50, y => 0];
$ent['p2'] = [x => 200, y => 350];
$t = 1;

for ($i = 0;$i < 300;$i += 50)
{
    $model2d->lineWidth($t);
    $model2d->drawLine($ent);
    $t++;
    $ent['p1']['x'] += 50;
    $ent['p2']['y'] -= 50;
}

$fname = "cadline.png";
imagePng($model2d->canvas, './images/'.$fname);
imagedestroy($model2d->canvas);

echo "<img src='images/$fname?u=".time()."'/>";
?>
