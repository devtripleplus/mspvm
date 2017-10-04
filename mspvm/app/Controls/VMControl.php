<?php namespace App\Controls;

use App\VM;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

Abstract class VMControl {
    /**
     * Name of the control, as displayed on the button
     *
     * @var
     */
    protected $name;

    /**
     * The order of the control
     *
     * @TODO Implement
     *
     * @var
     */
    protected $order;

    /**
     * The level of the control
     *
     * 1 = client | 2 = intermediate | 3 = admin
     *
     * @TODO Enforce
     *
     * @var
     */
    protected $level;

    abstract public function execute(VM $vps, &$logEntry, $data = null);

    public function form(VM $vm) {
        return [];
    }

    /**
     * @param $request
     * @param $validator
     * @return null|array
     */
    public function validate($request, &$validator) {
        return [];
    }

    /**
     * @return \Illuminate\Contracts\Validation\Factory
     */
    private function getValidator() {
        return app('Illuminate\Contracts\Validation\Factory');
    }

    public function validateData(Request $request) {
        $rules = null;

        if (($errors = $this->validate($request, $rules)) != null) {
            return $errors;
        }

        $validator = $this->getValidator()->make($request->all(), $rules);

        if (is_array($rules) && $validator->fails()) {
            return $validator->getMessageBag()->all();
        }

        return [];
    }

    public function hasForm(VM $vm) {
        return is_array($this->form($vm)) && !empty($this->form($vm));
    }

    /**
     * Get the form items
     *
     * This method makes use of the misc/control_form_item template to generate the template for each item
     *
     * @return array
     */
    public function getFormItems(VM $vm) {
        $items = [];
        foreach ($this->form($vm) as $formItem) {
            if (isset($formItem['name'])) {
                $name = $formItem['name'];
            } else {
                $name = strtolower($formItem['label']);
            }

            if (!isset($formItem['type'])) {
                $formItem['type'] = 'text';
            }

            switch ($formItem['type']) {
                case 'select':
                    $item = \Form::select($name, $formItem['options'], null, ['class' => 'form-control']);
                    break;
                case 'password':
                    $item = \Form::password($name, ['class' => 'form-control']);
                    break;
                default:
                    $item = \Form::text($name, array_get($formItem, 'value', null), ['class' => 'form-control']);
            }

            $items[] = view('misc/control_form_item', [
               'item' => $item,
                'label' => $formItem['label']
            ]);
        }

        return $items;
    }

    public function getIcon() {
        return '<i class="fa fa-play"></i>';
    }

    public function getUrl(VM $vm) {
        return app('url')->to('vmc/'.$vm->id.'/?c='.$this->getSlug());
    }

    public function getName() {
        return $this->name;
    }

    public function getOrder() {
        return $this->order;
    }

    public function getLevel() {
        return $this->level;
    }

    public function display(VM $vm) {
        return true;
    }

    public function getSlug() {
        return $this->slug;
    }
}