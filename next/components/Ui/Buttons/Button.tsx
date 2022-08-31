import { classNames } from "../../../utils/helpers";

export interface ButtonProps {
  children: string | string[] | JSX.Element[] | JSX.Element;
  className?: string;
  onClick?: (e: React.MouseEvent<HTMLButtonElement>) => void;
}

const Button = (props: ButtonProps): JSX.Element => {
  return (
    <button
      onClick={props.onClick}
      className={classNames(
        props.className,
        "my-3 items-center border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-lakasir-primary outline-none"
      )}
    >
      {props.children}
    </button>
  );
}

export { Button };
