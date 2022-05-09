<?php declare(strict_types=1);

namespace WebmozartAssertBug130;

use Webmozart\Assert\Assert;

class Bug130
{
	/** @var array<int, array{id: string, num_order: string}> */
	protected $orders = [];

	public function setOrders(array $orders): self
	{
		Assert::allCount($orders, 2);
		Assert::allKeyExists($orders, 'id');
		Assert::allKeyExists($orders, 'num_order');

		$this->orders = $orders;

		return $this;
	}
}
