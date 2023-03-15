import axios from "axios";
import gql from "graphql-tag";
import ApolloClient from "apollo-client";
import { ApolloLink } from "apollo-link";
import { InMemoryCache } from "apollo-cache-inmemory";
import { createUploadLink } from "apollo-upload-client";

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

  #client: ApolloClient<any>;

  constructor() {
    this.#client = new ApolloClient({
      link: ApolloLink.from([
        createUploadLink({
          uri: import.meta.env.VITE_API_ENDPOINT
        })
      ]),
      cache: new InMemoryCache({
        addTypename: false
      })
    });
  }

  /**
   * Main method to send reuqest for graphql.
   * @param {string} query
   * @param {ParamsType<T>, undefined}params
   * @returns {Promise<K | undefined>}
   */
  public async request<T, K>(
    query: string,
    params?: ParamsType<T>,
    type: "axios" | "apollo" = "axios"
  ): Promise<K | undefined> {
    return type === "axios"
      ? await this.#axiosService(query, params)
      : await this.#apolloService(query, params);
  }

  /**
   * Method to set apollo service for query.
   * @param {string} query
   * @param {ParamsType<any>} params
   * @returns
   */
  async #apolloService(query: string, params?: ParamsType<any>) {
    return await this.#client.mutate({
      mutation: gql`
        ${query}
      `,
      variables: params,
      context: {
        hasUpload: true
      },
      fetchPolicy: "no-cache"
    });
  }

  /**
   * Method to set axios service for query.
   * @param {string} query
   * @param {ParamsType<any>} params
   * @returns
   */
  async #axiosService(query: string, params?: ParamsType<any>) {
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
