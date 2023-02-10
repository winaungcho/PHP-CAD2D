
# PHP-CAD2D
CAD2D is a class to create an engineering drawing.
Entity data are stored in the associative array.
Class draw the drawing on image after pre-defined entity data.

![PHP-CAD2D](images/cadpoly.png)

## Usage

First include the `cad2d.php` class in your file, and use the class as following to create drawing.

```php
<?php
require_once ("cad2d.php");

$model2d = new CAD2D();
$model2d->setCanvas(200, 800, 1.5);
$model2d->drawGrid(-50, -50, 300, 600, 50);

$fname = "cad2dsample.png";
imagePng($model2d->canvas, './images/'.$fname);
imagedestroy($model2d->canvas);

echo "<img src='images/$fname?u=".time()."'/>";
?>
```

## Examples
### Draw Line
[Line drawing example code](example/cadline.php)
![PHP-CAD2D](images/cadline.png)

### Draw Polylines And Filling Hatch
[Poly Line and Hatch drawing example](example/cadpoly.php)
![PHP-CAD2D](images/cadpoly.png)

### Draw Ellipse And Filling Hatch
[Ellipse drawing example](example/cad2dellipse.php)
![PHP-CAD2D](images/cad2dellipse.png)

### Draw Dimension
[Dimension drawing example](example/cad2ddim.php)
![PHP-CAD2D](images/cad2ddim.png)

### Draw Text
[Text drawing example](example/cad2dtext.php)
![PHP-CAD2D](images/cad2dtext.png)

### Draw Rectangle And Points
[Rectangle and Point drawing example](example/cad2drectpt.php)
![PHP-CAD2D](images/cad2drectpt.png)

## Contact
Contact me for comercial use via mail winaungcho@gmail.com.

