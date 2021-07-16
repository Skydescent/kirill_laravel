<?php

namespace App\Http\Requests;

use App\Contracts\Repository\PostRepositoryContract;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed slug
 */
class PostStoreAndUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {

        if ($this->user()->isAdmin() && !$this->slug) {
            return ['published'=>''];
        }
        $slugRule = 'required|regex:/^[a-z0-9-_]+$/i|unique:posts';

        if ($this->route()->hasParameter('post')) {

            $slugRule .= ',slug,' . $this->getPostId($this->post);
        }

        return [
            'slug' => $slugRule,
            'title' => 'required|between:5,100',
            'short_text' => 'required|max:255',
            'body' => 'required',
            'published' => '',
            'tags' => '',
            'owner_id' => '',
        ];

    }

    /**
     * @throws BindingResolutionException
     */
    private function getPostId($slug)
    {
        return app()
            ->make(PostRepositoryContract::class)
            ->find($slug)->id;
    }
}
