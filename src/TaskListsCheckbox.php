<?php

namespace Lossendae\CommonMark\TaskLists;

use League\CommonMark\Inline\Element\AbstractInline;

/**
 * Class TaskListsCheckbox
 *
 * @see https://help.github.com/articles/writing-on-github/#task-lists
 * @package Lossendae\CommonMark\TaskLists
 */
class TaskListsCheckbox extends AbstractInline
{
    const CHECKED     = true;
    const NOT_CHECKED = false;

    /**
     * @var array
     *
     * Used for storage of arbitrary data
     */
    public $data = [];

    /**
     * @var string
     *
     * The checkbox status
     */
    protected $checked;

    /**
     * @param bool|false $checked
     * @param            $attributes
     */
    public function __construct($checked = self::NOT_CHECKED, array $attributes = [])
    {
        $this->checked = $checked;
        $this->data    = $attributes;
    }

    /**
     * @return bool
     */
    public function isChecked()
    {
        return $this->checked;
    }
}
