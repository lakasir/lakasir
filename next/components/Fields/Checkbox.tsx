import { useLayoutEffect, useState } from "react";

export interface ICheckbox {
  label?: string | JSX.Element;
  name: string;
  placeholder?: string;
  className?: string;
  info?: string;
  prepend?: string | JSX.Element;
  append?: string | JSX.Element;
  error?: string;
}

const Checkbox = (props: ICheckbox) => {
  const [checked, setChecked] = useState(false);
  useLayoutEffect(() => {
    // const checkbox = document.getElementById(`id-input-${props.name}`);
    // setChecked(checkbox?.getAttribute("value") ? true : false);
  });
  return (
    <div className="relative flex items-start my-1">
      <div className="flex items-center h-5">
        <input
          type="checkbox"
          name={props.name}
          id={`id-input-${props.name}`}
          checked={checked}
          className="focus:ring-lakasir-primary h-4 w-4 text-indigo-600 border-gray-300 rounded"
          onChange={(e) => {
            if (e.target.checked) {
              setChecked(true);
            } else {
              setChecked(false);
            }
          }}
        />
      </div>
      <div className="ml-3 text-sm">
        {props.label ? (
          <label
            htmlFor={`id-input-${props.name}`}
            className="font-medium text-gray-700"
          >
            {props.label}
          </label>
        ) : (
          ""
        )}
        {props.info ? (
          <p id="comments-description" className="text-gray-500">
            {props.info}
          </p>
        ) : (
          ""
        )}
      </div>
    </div>
  );
};

export default Checkbox;
