<?php


namespace FluentTraversable;


use PhpOption\Option;

/**
 * Class that can be used to compose complex functions that operates on arrays.
 *
 * @author Piotr Śliwa <peter.pl7@gmail.com>
 */
class TraversableShaper implements TraversableFlow
{
    private $operations = array();
    private $terminalOperation;

    protected function __construct()
    {
    }

    /**
     * Creates empty shaper
     *
     * @return TraversableShaper
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @inheritdoc
     *
     * @return TraversableShaper
     */
    public function map($func)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return TraversableShaper
     */
    public function flatMap($func)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return TraversableShaper
     */
    public function filter($predicate)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return TraversableShaper
     */
    public function unique()
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return TraversableShaper
     */
    public function group($keyFunction)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return TraversableShaper
     */
    public function order($comparator = null)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return TraversableShaper
     */
    public function skip($i)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return TraversableShaper
     */
    public function limit($i)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return TraversableShaper
     */
    public function partition($predicate)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return TraversableShaper
     */
    public function intersect($traversable)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return TraversableShaper
     */
    public function difference($traversable)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return TraversableShaper
     */
    public function keys()
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return TraversableShaper
     */
    public function append($element)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return TraversableShaper
     */
    public function allMatch($predicate)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return TraversableShaper
     */
    public function anyMatch($predicate)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return TraversableShaper
     */
    public function noneMatch($predicate)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return TraversableShaper
     */
    public function size()
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function min($comparator = null)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this->optionPuppet();
    }

    /**
     * @inheritdoc
     */
    public function firstMatch($predicate)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this->optionPuppet();
    }

    /**
     * @inheritdoc
     *
     * @return TraversableShaper
     */
    public function toArray()
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return TraversableShaper
     */
    public function toMap()
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return TraversableShaper
     */
    public function collect($collector)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return TraversableShaper
     */
    public function join($separator)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function first()
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this->optionPuppet();
    }

    /**
     * @inheritdoc
     */
    public function last()
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this->optionPuppet();
    }

    /**
     * @inheritdoc
     */
    public function reduce($binaryOperator)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this->optionPuppet();
    }


    /**
     * @inheritdoc
     * @return TraversableShaper
     */
    public function reduceFromIdentity($identity, $binaryOperation)
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
     * Applies shaper on given traversable
     *
     * @param array|\Traversable $traversable
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