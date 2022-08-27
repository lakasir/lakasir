import { ExclamationCircleIcon } from "@heroicons/react/solid";
import { classNames } from "../../../utils/helpers";

export interface IOptionSelect {
  label: string | number | undefined | JSX.Element;
  value: string | number | undefined;
}

interface ExtendProps {
  errorIcon?: boolean;
}

export interface SelectProps {
  label?: string | JSX.Element;
  name: string;
  placeholder?: string;
  className?: string;
  prepend?: string | JSX.Element;
  append?: string | JSX.Element;
  error?: string;
  value?: string | number | undefined;
  options?: IOptionSelect[];
  disable?: ExtendProps;
}

export function Select(props: SelectProps): JSX.Element {
  return (
    <div>
      {props.label ? (
        <label
          htmlFor={`id-input-${props.name}`}
          className="block text-sm font-medium text-gray-700"
        >
          {props.label}
        </label>
      ) : (
        ""
      )}
      <div className="flex mt-1 rounded-md shadow-sm">
        {props.prepend}
        <select
          name={props.name}
          id={`id-input-${props.name}`}
          className={classNames(
            props.className,
            "p-3 transition ease-in-out border-2 shadow-sm block w-full sm:text-sm rounded-lg bg-transparent",
            "border-gray-300 focus:outline-none focus:border-2 focus:ring-lakasir-primary focus:border-lakasir-primary"
          )}
          placeholder={props.placeholder}
        >
          {props.options?.map((option, index) => (
            <option key={index} value={option.value}>
              {option.label}
            </option>
          ))}
        </select>
        {props.error ? (
          <>
            {!props.disable?.errorIcon ? (
              <div className="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <ExclamationCircleIcon
                  className="h-5 w-5 text-red-500"
                  aria-hidden="true"
                />
              </div>
            ) : (
              props.append
            )}
          </>
        ) : (
          props.append
        )}
      </div>
      {props.error ? (
        <p className="mt-1 text-sm text-red-600" id="email-error">
          {props.error}
        </p>
      ) : (
        ""
      )}
    </div>
  );
}

export default Select;
