import { classNames } from "@/utils/helpers";
import { ExclamationCircleIcon } from "@heroicons/react/solid";
import { FocusEventHandler } from "react";

export interface InputProps {
  label?: string | JSX.Element;
  name: string;
  placeholder?: string;
  className?: string;
  type: "text" | "number" | "date";
  prepend?: string | JSX.Element;
  append?: string | JSX.Element;
  error?: string;
  value?: string | number | readonly string[] | undefined;
  onFocus?: FocusEventHandler<HTMLInputElement> | undefined;
}

export function Input(props: InputProps): JSX.Element {
  return (
    <div>
      <label
        htmlFor={`id-input-${props.name}`}
        className="block text-sm font-medium text-gray-700"
      >
        {props.label}
      </label>
      <div className="mt-1 relative rounded-md shadow-sm">
        {props.prepend}
        <input
          onFocus={props.onFocus}
          type={props.type}
          name={props.name}
          id={`id-input-${props.name}`}
          value={props.value}
          className={classNames(
            props.className,
            "p-3 transition ease-in-out border-2 shadow-sm block w-full sm:text-sm rounded-lg",
            props.error
              ? "text-red-900 border-red-300 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500"
              : "border-gray-300 focus:outline-none focus:border-2 focus:ring-lakasir-primary focus:border-lakasir-primary"
          )}
          placeholder={props.placeholder}
        />
        {props.error ? (
          <div className="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <ExclamationCircleIcon
              className="h-5 w-5 text-red-500"
              aria-hidden="true"
            />
          </div>
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

export default Input;
