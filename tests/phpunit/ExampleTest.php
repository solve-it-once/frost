<?php

namespace phpunit;

use PHPUnit\Framework\TestCase;

/**
 * Class ExampleTest does a self-evident assertion to ensure phpunit is working.
 */
class ExampleTest extends TestCase {

  /**
   * Asserts that universal laws are constant.
   */
  public function testExample() {
    $this->assertEquals(1, 1, "One equals one!");
  }

}
