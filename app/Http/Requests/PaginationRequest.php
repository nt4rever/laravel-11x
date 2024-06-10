<?php

namespace App\Http\Requests;

class PaginationRequest extends Request
{
    public const PER_PAGE = 15;

    public function prepareForValidation()
    {
        $input = $this->all();

        if (isset($input['per_page'])) {
            $input['per_page'] = $this->toPerPage($input['per_page']);
        }

        $this->replace($input);
    }

    private function toPerPage($perPage)
    {
        if (empty($perPage) || !is_integer($perPage)) {
            return self::PER_PAGE;
        }

        if ($perPage >= 1000 || $perPage < 0) {
            return self::PER_PAGE;
        }

        return $perPage;
    }
}
