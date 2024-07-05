<?php

namespace app\models;

use app\core\db\DbModel;
use Exception;
use PDO;

class Photo extends DbModel
{
    public string $name = '';
    public string $path = '';
    public int $user_id = 0;


    public function tableName(): string
    {
        return 'photos';
    }

    public function attributes(): array
    {
        return [
            'name', 'path', 'user_id'
        ];
    }

    public function primaryKey(): string
    {
        return 'id';
    }

    public function rules(): array
    {
        return [
            'name' => [self::RULE_REQUIRED],
            'path' => [self::RULE_REQUIRED, [self::RULE_UNIQUE, 'class' => self::class]]
        ];
    }

    public function findAllAuthUser($where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    public function findAll()
    {
        $tableName = static::tableName();
        $statement = self::prepare("SELECT * FROM $tableName");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    public function delete()
    {
        $photo = $this->findOne(['id' => $this->id]);

        if (!$photo) {
            throw new Exception('Photo not found.');
        }

        if (file_exists($photo->path)) {
            unlink($photo->path);
        }

        $tableName = static::tableName();
        $statement = self::prepare("DELETE FROM $tableName WHERE id = :id");
        $statement->bindValue(':id', $this->id);

        if (!$statement->execute()) {
            throw new Exception('Failed to delete photo.');
        }

        return true;
    }
}