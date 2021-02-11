<?php

namespace App\Helpers;

/**
 * StatusHelper
 *
 * @package App\Helpers
 */
class StatusHelper
{
    /**
     * @var string
     */
    const ACTIVE = 'active';

    /**
     * @var string
     */
    const INACTIVE = 'inactive';

    /**
     * @var array
     */
    private static $data = [
        [
            'id' => self::ACTIVE,
            'nome' => 'Ativo',
            'alert-class' => 'alert alert-success'
        ], [
            'id' => self::INACTIVE,
            'nome' => 'Inativo',
            'alert-class' => 'alert alert-danger'
        ]
    ];

    /**
     * Get list
     *
     * @return array
     */
    public static function getList()
    {
        return self::$data;
    }

    /**
     * Get
     *
     * @param int $id
     * @return array
     */
    public static function get($id)
    {
        $status = ['id' => 0, 'nome' => '', 'alert-class' => ''];
        foreach (self::$data as $item) {
            if ($item['id'] === $id) {
                $status = $item;
                break;
            }
        }
        return $status;
    }
}
