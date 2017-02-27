<?php
namespace Modular\Relationships;

/**
 * Adds a multiple free text Tags relationship TagField to Tag model to extended model.
 *
 * @package Modular\Fields
 */

use Modular\Models\Tag;

class HasTags extends HasManyMany {
	const Name = 'Tags';
	const Schema = 'Modular\Models\Tag';

	private static $multiple_tags = true;

	private static $can_create_tags = true;

	private static $allow_sorting = false;

	public function cmsFields($mode = null) {
		return [
			(new \TagField(
				static::field_name(),
				'',
				$this->availableTags()
			))->setIsMultiple(
				(bool)$this->config()->get('multiple_tags')
			)->setCanCreate(
				(bool)$this->config()->get('can_create_tags')
			),
		];
	}


	protected function availableTags() {
		return Tag::get()->sort('Title');
	}
}