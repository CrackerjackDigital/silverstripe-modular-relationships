<?php
namespace Modular\Relationships;

use Modular\Traits\cache;
use Modular\GridField\GridField;
use Modular\Helpers\Strings;
use Modular\Types\RefManyManyType;

class HasManyMany extends RelatedModels implements RefManyManyType {
	use cache;

	const RelationshipPrefix  = '';
	const ShowAsTagsField     = 'tags';
	const GridFieldConfigName = 'Modular\GridField\HasManyManyGridFieldConfig';

	/**
	 * Adds many_many relationships based off relationship_name and related_class_name, and many_many_extraFields such as 'Sort'.
	 *
	 * @param null $class
	 * @param null $extension
	 * @return array
	 */
	public function extraStatics($class = null, $extension = null) {
		$statics = parent::extraStatics($class, $extension) ?: [];

		if ($name = static::relationship_name('')) {
			$extra = [];

			if (static::sortable()) {
				$extra = [
					'many_many_extraFields' => [
						$name => [
							static::SortFieldName => 'Int',
						],
					],
				];
			}

			$statics = array_merge_recursive(
				$statics,
				$extra,
				[
					'many_many' => [
						$name => static::related_class_name(),
					],
				]
			);
		}
		return $statics;

	}

	/**
	 * Add a csv list of implementors of this class as token 'implementors'
	 *
	 * @return mixed
	 */
	public function fieldDecorationTokens() {
		return array_merge(
			parent::fieldDecorationTokens(),
			[
				'implementors' => implode(', ', $this->implementors()),
			]
		);
	}

	/**
	 * Return a map of derived implementations and their singular names.
	 *
	 * @param bool $includeCalledClass if true then the class being called will also be in the returned map
	 * @return array [ className => name ]
	 */
	public static function implementors($includeCalledClass = false) {
		$calledClass = get_called_class();

		if (!$implementors = static::cache("$calledClass-implementors")) {
			$implementors = [];
			// iterate through children of 'HasRelatedPages', eg 'BusinessPages', 'DivisionPages' etc
			foreach (\ClassInfo::subclassesFor($calledClass) as $className) {
				if (($className == $calledClass) && !$includeCalledClass) {
					// skip the related pages class itself if not included
					continue;
				}
				$implementors[ $className ] = $className::relationship_name();
			}
			static::cache("$calledClass-implementors", $implementors);
		}
		return $implementors ?: [];
	}

	/**
	 * Returns a field array using a tag field which can be used in derived classes instead of a GridField which is the default returned by cmsFields().
	 *
	 * @return array
	 */
	protected function tagFields() {
		$multipleSelect = (bool) $this->config()->get('multiple_select');
		$canCreate = (bool) $this->config()->get('allow_add_new');

		$schema = static::schema();

		return [
			(new \TagField(
				static::relationship_name(),
				null,
				$schema::get()
			))->setIsMultiple($multipleSelect)->setCanCreate($canCreate),
		];
	}

}