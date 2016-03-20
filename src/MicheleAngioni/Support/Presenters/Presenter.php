<?php namespace MicheleAngioni\Support\Presenters;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class Presenter
{

	/**
	 * Return an instance of a Model wrapped in a presenter object
	 *
	 * @param  Model $model
	 * @param  PresentableInterface $presenter
	 *
	 * @return Model
	 */
	public function model(Model $model, PresentableInterface $presenter)
	{
		$object = clone $presenter;

		$object->set($model);

		return $object;
	}

	/**
	 * Return an instance of a Collection with each value wrapped in a presenter object
	 *
	 * @param  Collection $collection
	 * @param  PresentableInterface $presenter
	 *
	 * @return Collection
	 */
	public function collection(Collection $collection, PresentableInterface $presenter)
	{
		foreach ($collection as $key => $value) {
			$collection->put($key, $this->model($value, $presenter));
		}

		return $collection;
	}

	/**
	 * Return an instance of a Paginator with each value wrapped in a presenter object
	 *
	 * @param  Paginator $paginator
	 * @param  PresentableInterface $presenter
	 *
	 * @return Paginator
	 */
	public function paginator(Paginator $paginator, PresentableInterface $presenter)
	{
		$items = [];

		foreach ($paginator->getItems() as $item) {
			$items[] = $this->model($item, $presenter);
		}

		$paginator->setItems($items);

		return $paginator;
	}

}
