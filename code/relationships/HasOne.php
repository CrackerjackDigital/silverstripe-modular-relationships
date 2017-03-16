<?php
namespace Modular\Relationships;

use Modular\Field;
use Modular\TypedField;
use Modular\Types\RefOneType;
use Modular\Types\RefType;

abstract class HasOne extends TypedField implements RefOneType {
	const Name    = '';
	const RelatedKeyField     = 'ID';
	const RelatedDisplayField = 'Title';

	private static $tab_name = 'Root.Main';

	/**
	 * Add has_one relationships to related class.
	 *
	 * @param null $class
	 * @param null $extension
	 * @return mixed
	 */
	public function extraStatics($class = null, $extension = null) {
		return array_merge_recursive(
			parent::extraStatics($class, $extension) ?: [],
			[
				'has_one' => [
					static::relationship_name() => static::related_class_name(),
				],
			]
		);
	}

	/**
	 * Add a drop-down with related classes from Schema using RelatedKeyField and RelatedDisplayField.
	 *
	 * @param $mode
	 * @return array
	 */
	public function cmsFields($mode = null) {
		return [
			new \DropdownField(
				static::related_field_name(),
				static::relationship_name(),
				static::options()
			),
		];
	}

	/**
	 * has_one relationships need an 'ID' appended to the relationship name to make the field name
	 *
	 * @param string $suffix defaults to 'ID'
	 * @return string
	 */
	public static function related_field_name($suffix = 'ID') {
		return static::field_name() . $suffix;
	}

	/**
	 * Return unadorned has_one related class name.
	 *
	 * @return string
	 */
	public static function related_class_name() {
		return static::schema();
	}

	/**
	 * Returns the Name for this field if set, optionally appended with the fieldName as for a relationship.
	 *
	 * @param string $fieldName if supplied will be added on to Name with a '.' prefix
	 * @return string
	 */
	public static function relationship_name($fieldName = '') {
		return static::field_name() ? (static::field_name() . ($fieldName ? ".$fieldName" : '')) : '';
	}

	/**
	 * Return map of key field => title for the drop down where the relationship target can be chosen.
	 *
	 * @return array
	 */
	public static function options() {
		return \DataObject::get(static::schema())->map(static::RelatedKeyField, static::RelatedDisplayField)->toArray();
	}

}