/**
 * Function rules for check is value empty.
 * @param {boolean | string | number} value
 * @returns {string | null}
 */
export function isRequiredValidation(value: boolean | string | number): string | null {
  const messageError = 'This field is a required field.';
  return !value.toString()
    ? messageError
    : (value.toString().trim() !== '' ? null : messageError);
}

/**
 * Function rules for check is value has e-mail format.
 * @param {string} value
 * @returns {string | null }
 */
export function isEmailValidation(value: string): string | null {
  const messageError = 'The field has an incorrect e-mail format.';
  const pattern = new RegExp(/^([A-Z0-9._+-]+)@([A-Z0-9.-]+)\.([A-Z]{2,13})$/i);
  return pattern.test(value) ? null : messageError;
}