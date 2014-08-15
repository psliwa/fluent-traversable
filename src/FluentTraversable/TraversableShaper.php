<?php


namespace FluentTraversable;


use PhpOption\Option;

class TraversableShaper implements TraversableFlow
{
    private $operations = array();
    private $terminalOperation;

    protected function __construct()
    {
    }

    public static function create()
    {
        return new static();
    }

    /**
     * @param $func
     * @return TraversableShaper
     */
    public function map($func)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @param $func
     * @return TraversableShaper
     */
    public function flatMap($func)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @param $func
     * @return TraversableShaper
     */
    public function filter($func)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @return TraversableShaper
     */
    public function unique()
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @param $func
     * @return TraversableShaper
     */
    public function group($func)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @param $comparator
     * @return TraversableShaper
     */
    public function order($comparator = null)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @param $i
     * @return TraversableShaper
     */
    public function skip($i)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @param $i
     * @return TraversableShaper
     */
    public function limit($i)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @param $func
     * @return TraversableShaper
     */
    public function partition($func)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @param $traversable
     * @return TraversableShaper
     */
    public function intersect($traversable)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @param $traversable
     * @return TraversableShaper
     */
    public function difference($traversable)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @param $element
     * @return TraversableFlow
     */
    public function append($element)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @param $predicate
     * @return TraversableShaper
     */
    public function allMatch($predicate)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @param $predicate
     * @return TraversableShaper
     */
    public function anyMatch($predicate)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @param $predicate
     * @return TraversableShaper
     */
    public function noneMatch($predicate)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @return TraversableShaper
     */
    public function size()
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @param $comparator
     * @return Option
     */
    public function max($comparator = null)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this->optionPuppet();
    }

    /**
     * @return Option
     */
    private function optionPuppet()
    {
        $puppet = Puppet::record();
        $this->operations[] = $puppet;

        return $puppet;
    }

    /**
     * @param $comparator
     * @return Option
     */
    public function min($comparator = null)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this->optionPuppet();
    }

    /**
     * @param $func
     * @return Option
     */
    public function firstMatch($func)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this->optionPuppet();
    }

    /**
     * @return TraversableShaper
     */
    public function toArray()
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @return TraversableShaper
     */
    public function toMap()
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @param $collector
     * @return TraversableShaper
     */
    public function collect($collector)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @param $separator
     * @return TraversableShaper
     */
    public function join($separator)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @return Option
     */
    public function first()
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this->optionPuppet();
    }

    /**
     * @return Option
     */
    public function last()
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this->optionPuppet();
    }

    /**
     * @param $func
     * @return Option
     */
    public function reduce($func)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this->optionPuppet();
    }


    /**
     * @param $identity
     * @param $biOperation
     * @return TraversableShaper
     */
    public function reduceFromIdentity($identity, $biOperation)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @param $traversable
     * @return mixed Depends on shaper instrumentation
     */
    public function __invoke($traversable)
    {
        $result = FluentTraversable::from($traversable);

        foreach($this->operations as $operation) {
            if($operation instanceof Puppet) {
                $result = $operation($result);
            } else {
                list($method, $args) = $operation;

                $result = call_user_func_array(array($result, $method), $args);
            }
        }

        return $result;
    }

    /**
     * @param $traversable
     * @return mixed Depends on shaper instrumentation
     */
    public function apply($traversable)
    {
        return $this($traversable);
    }

    private function markTerminalOperation($method)
    {
        $this->checkTerminalOperation();

        $this->terminalOperation = $method;
    }

    private function checkTerminalOperation()
    {
        if ($this->terminalOperation !== null) {
            throw new \RuntimeException(
                sprintf(
                    'Only one terminal operation can be called, "%s" has been already called.',
                    $this->terminalOperation
                )
            );
        }
    }
}