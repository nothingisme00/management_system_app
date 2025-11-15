<?php

declare(strict_types=1);

namespace App\DTOs;

use JsonSerializable;

/**
 * Base Data Transfer Object
 *
 * Provides common functionality for all DTOs including
 * array conversion, JSON serialization, and immutability.
 */
abstract class BaseDTO implements JsonSerializable
{
    /**
     * Convert DTO to array.
     */
    public function toArray(): array
    {
        $data = [];

        foreach (get_object_vars($this) as $property => $value) {
            if ($value instanceof self) {
                $data[$property] = $value->toArray();
            } elseif (is_array($value)) {
                $data[$property] = array_map(
                    fn ($item) => $item instanceof self ? $item->toArray() : $item,
                    $value
                );
            } else {
                $data[$property] = $value;
            }
        }

        return $data;
    }

    /**
     * Create DTO from array.
     */
    public static function fromArray(array $data): static
    {
        return new static(...$data);
    }

    /**
     * JSON serialization.
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Convert to JSON string.
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }
}
