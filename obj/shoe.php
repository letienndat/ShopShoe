<?php
class Shoe {
    public $id;
    public $path_image;
    public $title;
    public $price;
    public $type;
    public $brain;
    public $manufacture;
    public $material;
    public $description;

    public function __construct($id, $path_image, $title, $price, $type, $brain, $manufacture, $material, $description) {
        $this->id = $id;
        $this->path_image = $path_image;
        $this->title = $title;
        $this->price = $price;
        $this->type = $type;
        $this->brain = $brain;
        $this->manufacture = $manufacture;
        $this->material = $material;
        $this->description = $description;
    }
}
