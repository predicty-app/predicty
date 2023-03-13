import axios from "axios";

type HeadersType = {
  "content-type": string;
};

type ParamsType<T> = {
  readonly [K in keyof T]: T[K];
};

interface ApiService {
  request<T, K>(query: string, params?: ParamsType<T>): Promise<K | undefined>;
}

/**
 * Main class of api service.
 * @implements {ApiService}
 */
class ApiService implements ApiService {
  /**
   * @var {HeadersType}
   */
  #headers: HeadersType = {
    "content-type": "application/json"
  };

  /**
   * Main method to send reuqest for graphql.
   * @param {string} query
   * @param {ParamsType<T>, undefined}params
   * @returns {Promise<K | undefined>}
   */
  public async request<T, K>(
    query: string,
    params?: ParamsType<T>
  ): Promise<K | undefined> {
    return (
      await axios({
        url: import.meta.env.VITE_API_ENDPOINT,
        method: "post",
        headers: this.#headers,
        data: {
          query,
          variables: params
        }
      })
    ).data;
  }
}

export default new ApiService();
