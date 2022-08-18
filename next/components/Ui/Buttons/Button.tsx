import { classNames } from "../../../utils/helpers";

export interface ButtonProps {
  children: string | string[] | JSX.Element[] | JSX.Element;
  className?: string;
}

function Button(props: ButtonProps): JSX.Element {
  return (
    <button
      className={classNames(
        props.className,
        "my-3 items-center border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-lakasir-primary outline-none focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-lakasir-primary"
      )}
    >
      {props.children}
    </button>
  );
}

export default Button;
