<?php

namespace App\Core;

class Validator
{
    protected array $errors = [];

    public function validate(array $data, array $rules): bool
    {
        $this->errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;
            if (is_string($fieldRules)) {
                $fieldRules = explode('|', $fieldRules);
            }

            foreach ($fieldRules as $rule) {
                $ruleName = $rule;
                $ruleParam = null;

                if (str_contains($rule, ':')) {
                    [$ruleName, $ruleParam] = explode(':', $rule, 2);
                }

                $this->applyRule($field, $value, $ruleName, $ruleParam, $data);
            }
        }

        return empty($this->errors);
    }

    protected function applyRule(string $field, mixed $value, string $rule, ?string $param, array $data): void
    {
        switch ($rule) {
            case 'required':
                if ($value === null || $value === '' || (is_array($value) && empty($value))) {
                    $this->addError($field, "فیلد {$field} الزامی است.");
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "فیلد {$field} باید یک ایمیل معتبر باشد.");
                }
                break;

            case 'phone':
                if (!empty($value)) {
                    $cleanPhone = Sanitizer::cleanPhone((string)$value);
                    if (!preg_match('/^09[0-9]{9}$/', $cleanPhone)) {
                        $this->addError($field, "شماره موبایل وارد شده معتبر نیست.");
                    }
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, "فیلد {$field} باید عدد باشد.");
                }
                break;

            case 'min':
                if (!empty($value)) {
                    if (is_numeric($value) && (float)$value < (float)$param) {
                        $this->addError($field, "فیلد {$field} نباید کمتر از {$param} باشد.");
                    } elseif (is_string($value) && mb_strlen($value, 'UTF-8') < (int)$param) {
                        $this->addError($field, "فیلد {$field} باید حداقل {$param} کاراکتر باشد.");
                    }
                }
                break;

            case 'max':
                if (!empty($value)) {
                    if (is_numeric($value) && (float)$value > (float)$param) {
                        $this->addError($field, "فیلد {$field} نباید بیشتر از {$param} باشد.");
                    } elseif (is_string($value) && mb_strlen($value, 'UTF-8') > (int)$param) {
                        $this->addError($field, "فیلد {$field} نباید بیشتر از {$param} کاراکتر باشد.");
                    }
                }
                break;

            case 'unique':
                if (!empty($value) && $param !== null) {
                    [$table, $column] = explode(',', $param);
                    $exists = Database::fetch("SELECT COUNT(*) as count FROM {$table} WHERE {$column} = ?", [$value]);
                    if ($exists && (int)$exists['count'] > 0) {
                        $this->addError($field, "مقدار فیلد {$field} قبلاً ثبت شده است.");
                    }
                }
                break;
        }
    }

    protected function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getFirstError(): ?string
    {
        foreach ($this->errors as $fieldErrors) {
            return $fieldErrors[0] ?? null;
        }
        return null;
    }
}
