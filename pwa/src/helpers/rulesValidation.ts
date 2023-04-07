/**
 * Function rules for check is value empty.
 * @param {boolean | string | number} value
 * @param {any} t
 * @returns {string | null}
 */
export function isRequiredValidation(
  value: boolean | string | number,
  t?: any
): string | null {
  const messageError = t("validation.is-required");
  return !value.toString()
    ? messageError
    : value.toString().trim() !== ""
    ? null
    : messageError;
}

/**
 * Function rules for check is value has e-mail format.
 * @param {string} value
 * @param {any} t
 * @returns {string | null }
 */
export function isEmailValidation(value: string, t?: any): string | null {
  const messageError = t("validation.is-email");
  const pattern = new RegExp(/^([A-Z0-9._+-]+)@([A-Z0-9.-]+)\.([A-Z]{2,13})$/i);
  return pattern.test(value) ? null : messageError;
}

/**
 * Function rules for check is passcode has correct value.
 * @param {string} value
 * @param {any} t
 * @returns {string | null }
 */
export function isPasscodeCorrectValidation(
  value: string | number,
  t?: any
): string | null {
  const messageError = t("validation.is-passcode-correct");
  return !Number.isInteger(value) || Number(value).toString().length != 6
    ? messageError
    : null;
}

/**
 * Function rules for check is password correct.
 * @param {string} value
 * @returns {string | null}
 */
export function isPasswordValidation(value: string): string | null {
  if (value.length < 8) {
    return 'The password must have 8 characters.';
  }
  if (!/[A-Z]/.test(value)) {
    return 'Password must contain one upper case letter.';
  }
  if (!/\d/.test(value)) {
    return 'Password must contain one number.';
  }
  if (/[ęóąśłżźćńĘÓĄŚŁŻŹĆŃ ]/.test(value)) {
    return 'The password cannot contain special characters.';
  }

  return null;
}