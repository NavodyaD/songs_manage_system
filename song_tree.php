<?php
class SongNode {
    public $name;
    public $preference;
    public $dateAdded;
    public $filePath;
    public $left;
    public $right;

    public function __construct($name, $preference, $dateAdded, $filePath) {
        $this->left = null;
        $this->right = null;
        $this->name = $name;
        $this->preference = $preference;
        $this->dateAdded = $dateAdded;
        $this->filePath = $filePath;
        
    }
}

class SongBST {
    private $root;

    public function __construct() {
        $this->root = null;
    }

    public function insert($name, $preference, $dateAdded, $filePath) {
        $this->root = $this->insertRec($this->root, $name, $preference, $dateAdded, $filePath);
    }

    private function insertRec($root, $name, $preference, $dateAdded, $filePath) {
        if ($root == null) {
            return new SongNode($name, $preference, $dateAdded, $filePath);
        }

        if ($preference < $root->preference) {
            $root->left = $this->insertRec($root->left, $name, $preference, $dateAdded, $filePath);
        } elseif ($preference > $root->preference) {
            $root->right = $this->insertRec($root->right, $name, $preference, $dateAdded, $filePath);
        } else {
            if ($dateAdded < $root->dateAdded) {
                $root->left = $this->insertRec($root->left, $name, $preference, $dateAdded, $filePath);
            } else {
                $root->right = $this->insertRec($root->right, $name, $preference, $dateAdded, $filePath);
            }
        }

        return $root;
    }

    public function inOrderPreferenceRange($minPref, $maxPref) {
        $songs = [];
        $this->inOrderPreferenceRangeRec($this->root, $minPref, $maxPref, $songs);
        return $songs;
    }

    private function inOrderPreferenceRangeRec($node, $minPref, $maxPref, &$songs) {
        if ($node != null) {
            if ($node->preference >= $minPref) {
                $this->inOrderPreferenceRangeRec($node->left, $minPref, $maxPref, $songs);
            }
    
            if ($node->preference >= $minPref && $node->preference <= $maxPref) {
                $songs[] = ['name' => $node->name, 'preference' => $node->preference, 'date_added' => $node->dateAdded, 'file_path' => $node->filePath];
            }

            if ($node->preference <= $maxPref) {
                $this->inOrderPreferenceRangeRec($node->right, $minPref, $maxPref, $songs);
            }
        }
    }

    public function inOrderDate() {
        $songs = [];
        $this->inOrderDateRec($this->root, $songs);
        return $songs;
    }

    private function inOrderDateRec($node, &$songs) {
        if ($node != null) {
            $this->inOrderDateRec($node->left, $songs);
            $songs[] = ['name' => $node->name, 'preference' => $node->preference, 'date_added' => $node->dateAdded, 'file_path' => $node->filePath];
            $this->inOrderDateRec($node->right, $songs);
        }
    }
}
?>
