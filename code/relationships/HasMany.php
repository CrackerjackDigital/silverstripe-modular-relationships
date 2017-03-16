<?php
namespace Modular\Relationships;

use Modular\Types\RefManyType;
use Modular\Types\RefType;

class HasMany extends RelatedModels implements RefManyType {
	const GridFieldConfigName = 'Modular\GridField\HasManyGridFieldConfig';

	public function extraStatics($class = null, $extension = null) {
		return array_merge_recursive(
			parent::extraStatics($class, $extension) ?: [],
			[
				'has_many' => [
					static::field_name() => static::schema()
				]
			]
		);
	}
}