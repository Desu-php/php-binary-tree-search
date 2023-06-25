<?php

namespace App;
class BinarySearchTree
{
    private null|Node|\stdClass $root = null;

    private int $iteration = 0;

    private array $result = [];

    public function createIndex(array $data, string $field): self
    {
        foreach ($data as $document) {
            if (isset($document[$field])) {
                $this->insert($document, $field);
            }
        }

        $this->toJSONSave($field);

        return $this;
    }

    private function toJSONSave(string $field): void
    {
        if (!empty($this->root)){
            file_put_contents($this->getIndexFileName($field), $this->getSerializedTree());
        }
    }

    public function insert($data, string $field): void
    {
        $node = new Node($data);

        if ($this->root === null) {
            $this->root = $node;
        } else {
            $this->insertNode($this->root, $node, $field);
        }
    }

    private function insertNode(&$root, &$node, string $field): void
    {
        if ($root === null) {
            $root = $node;
        } else {
            if ($node->data[$field] < $root->data[$field]) {
                $this->insertNode($root->left, $node, $field);
            } else {
                $this->insertNode($root->right, $node, $field);
            }
        }
    }

    private function getSerializedTree(): string
    {
        return json_encode($this->serializeTree($this->root));
    }

    private function serializeTree(?Node $node): ?array
    {
        if ($node === null) {
            return null;
        }

        return [
            'data' => $node->data,
            'left' => $this->serializeTree($node->left),
            'right' => $this->serializeTree($node->right),
        ];
    }

    public function search($field, $value): array
    {
        $this->iteration = 0;
        $this->result = [];

        $this->getIndex($field);

        $this->inorder($this->root, $field, $value);

        return $this->result;
    }

    private function inorder($root, string $field, string $value): void
    {
        if ($root && isset($root->data->{$field})) {
            $this->iteration++;

            $rootValue = $root->data->{$field};

            if ($rootValue === $value) {
                $this->result[] = [
                    'document' => $root->data,
                    'iteration' => $this->iteration
                ];
            }

            if ($value < $rootValue) {
                $this->inorder($root->left, $field, $value);
            } else {
                $this->inorder($root->right, $field, $value);
            }
        }
    }

    private function getIndex(string $field): void
    {
        $fileName = $this->getIndexFileName($field);

        if (file_exists($fileName)) {
            $this->root = json_decode(file_get_contents($this->getIndexFileName($field)));
        } else {
            $this->root = null;
        }
    }

    private function getIndexFileName(string $field): string
    {
        return "index-$field.json";
    }
}