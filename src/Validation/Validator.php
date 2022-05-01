<?php

namespace Xerophy\Framework\Validation;

use Xerophy\Framework\Validation\Rules\Contracts\RuleInterface;

class Validator
{
    use Resolver;

    /*
     *
     * */
    protected array $data;

    /*
     *
     * */
    protected array $rules;

    /*
     * ErrorBag instance
     * */
    protected ErrorBag $errorBag;

    /**
     * Create new validator instance
     *
     * @return void
     * */
    public function __construct()
    {
        $this->errorBag = new ErrorBag();
    }

    public function getErrors()
    {
        return $this->errorBag->errors;
    }

    /**
     *
     *
     * @param array $data
     * @return void
     * */
    public function make(array $data)
    {
        $data = array_map(function ($item) {
            $item['rules'] = $this->resolve($item['rules']);
            return $item;
        }, $data);

        $this->validate($data);
    }

    /**
     * validate the data
     *
     * @param array $data
     * @return void
     * */
    public function validate(array $data)
    {
        foreach ($data as $key => $value) {
            $this->applyRule($key, $value['content'], $value['rules']);
        }
    }

    /**
     *
     *
     * @param string $fieldName
     * @param string $fieldContent
     * @param array  $rules
     * */
    protected function applyRule(string $fieldName, string $fieldContent, array $rules)
    {
        foreach ($rules as $rule) {
            $rule = $this->isRuleArray($rule);
            $bool = $rule->apply($fieldName, $fieldContent, []);
            if($bool === false) {
                $this->errorBag->add($fieldName, Message::generate($fieldName, $rule));
            }
        }
    }

    /**
     * Checks if the rule is array and create and return the Rule
     *
     * @param string|array $rule
     * @return RuleInterface
     * */
    protected function isRuleArray(string|array $rule): RuleInterface
    {
        if(is_array($rule)) {
            return $this->createRule($rule[0], array_slice($rule, 1));
        }

        return $this->createRule($rule);
    }

    /**
     * Create the rule and pass the params
     *
     * @param string $rule
     * @param array $params
     *
     * @return RuleInterface
     * */
    protected function createRule(string $rule, array $params = []): RuleInterface
    {
        return new $rule(...$params);
    }
}