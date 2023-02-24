export interface StrategyProviders {
  initProvider(): void
}

/**
 * The ContextProviders defines the interface of interest to clients.
 */
export default class ContextProviders {
  /**
   * The Context maintains a reference to one of the StrategyProviders
   * objects. The Context does not know the concrete class of a strategy. It
   * should work with all strategies via the StrategyProviders interface.
   * @type {StrategyProviders}
   */
  #strategy: StrategyProviders;

  /**
   * ContextProviders accepts a strategy through the constructor, but also
   * provides a setter to change it at runtime.
   */
  constructor(strategy: StrategyProviders) {
    this.#strategy = strategy;
  }

  /**
   * ContextProviders allows replacing a StrategyProviders object at runtime.
   */
  public setStrategy(strategy: StrategyProviders) {
    this.#strategy = strategy;
  }


  /**
   * The ContextProviders delegates some work to the StrategyProviders object instead of
   * implementing multiple versions of the algorithm on its own.
   */
  public loginClient(): void {

    this.#strategy.initProvider()
  }
}