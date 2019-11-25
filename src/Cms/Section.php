<?php

namespace Kirby\Cms;

use Kirby\Exception\InvalidArgumentException;
use Kirby\Toolkit\Component;

/**
 * Section
 *
 * @package   Kirby Cms
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier GmbH
 * @license   https://getkirby.com/license
 */
class Section extends Component
{
    /**
     * Registry for all component mixins
     *
     * @var array
     */
    public static $mixins = [];

    /**
     * Registry for all component types
     *
     * @var array
     */
    public static $types = [];


    public function __construct(string $type, array $attrs = [])
    {
        if (isset($attrs['model']) === false) {
            throw new InvalidArgumentException('Undefined section model');
        }

        // use the type as fallback for the name
        $attrs['name'] = $attrs['name'] ?? $type;
        $attrs['type'] = $type;

        parent::__construct($type, $attrs);
    }

    /**
     * @return mixed
     */
    public function api()
    {
        if (isset($this->options['api']) === true && is_callable($this->options['api']) === true) {
            return $this->options['api']->call($this);
        }
    }

    /**
     * @return \Kirby\Cms\App
     */
    public function kirby()
    {
        return $this->model->kirby();
    }

    /**
     * @return \Kirby\Cms\Model
     */
    public function model()
    {
        return $this->model;
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        $array['type'] = $this->type();

        unset($array['model']);

        return $array;
    }

    public function toResponse(): array
    {
        return array_merge([
            'status' => 'ok',
            'code'   => 200,
            'name'   => $this->name,
            'type'   => $this->type
        ], $this->toArray());
    }
}
