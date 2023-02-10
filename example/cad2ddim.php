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

$ent = [
    'p1' => [
        'x' => 0,
        'y' => 0
    ],
    'p2' => [
        'x' => 200,
        'y' => 400
    ]
];
$model2d->lineWidth(4);
$model2d->drawLine($ent);
$model2d->drawDimAllign($ent);

$ent = array(
    'p2' => array(
        'x' => 200,
        'y' => 0
    ) ,
    'p1' => array(
        'x' => 200,
        'y' => 400
    )
);
$ent['text'] = "Length";
$model2d->drawLine($ent);
$model2d->drawDimAllign($ent);

$ent = [
    'p1' => [
        'x' => 0,
        'y' => 0
    ],
    'p2' => [
        'x' => 200,
        'y' => 0
    ]
];
$ent['dist'] = -60;
$ent['text'] = null;
$model2d->drawLine($ent);
$model2d->updateDimStyle([
	'tsize'=>30, 'tcolor' => RED, 'extlen' => 30,
	'arrsize' => 30
]);
$model2d->drawDimAllign($ent);

$fname = "cad2ddim.png";
imagePng($model2d->canvas, './images/'.$fname);
imagedestroy($model2d->canvas);

echo "<img src='images/$fname?u=".time()."'/>";
?>
