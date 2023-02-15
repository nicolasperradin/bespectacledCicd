import qs from "qs";
import type { SubmissionErrors } from "../types/error";
import { SubmissionError } from "./error";
import { ENTRYPOINT } from "./config";

const MIME_TYPE = "application/ld+json";

export default async function (id: string, options: any = {}) {
  if (typeof options.headers === "undefined") {
    Object.assign(options, { headers: new Headers() });
  }

  // const user = JSON.parse(localStorage.getItem("user") || ';

  if (options.headers.get("Accept") === null) {
    options.headers.set("Accept", MIME_TYPE);
  }

  if (
    options.body !== undefined &&
    !(options.body instanceof FormData) &&
    options.headers.get("Content-Type") === null
  ) {
    options.headers.set("Content-Type", MIME_TYPE);
  }

  if (options.params) {
    const queryString = qs.stringify(options.params);
    id = `${id}?${queryString}`;
  }

  // enable CORS for all requests
  Object.assign(options, {
    mode: "cors",
    // credentials: 'include', // when credentials needed
  });

  const response = await fetch(new URL(ENTRYPOINT + '/' + id.replace('/api/', '')), options);

  if (!response.ok) {
    const data = await response.json();
    const error = data["hydra:description"] || response.statusText;
    if (!data.violations) throw Error(error);

    const errors: SubmissionErrors = { _error: error };
    data.violations.forEach(
      (violation: { propertyPath: string; message: string }) => {
        errors[violation.propertyPath] = violation.message;
      }
    );

    throw new SubmissionError(errors);
  }

  return response;
}
