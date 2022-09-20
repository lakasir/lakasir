import { MouseEventHandler } from "react";

interface IActionOption {
  label: string;
  onClick: () => void;
}

interface ICardInterface {
  confirmable?: () => void;
  onClick?: (e: MouseEventHandler<HTMLDivElement>) => void;
  image?: JSX.Element;
  label: string;
  description?: string;
  sub_description?: string;
  action?: IActionOption[];
  id?: number;
}

const Card = (props: ICardInterface) => {
  return (
    <div className="flex" id="action-detail">
      <div
        className="w-full rounded-lg overflow-hidden cursor-pointer"
        id={`item-list-${props.id}`}
      >
        <div className="flex items-center" onClick={props.onClick}>
          {props.image ? (
            <div className="bg-lakasir-primary w-1/5 rounded-lg mr-2 flex justify-center items-center">
              {props.image}
            </div>
          ) : (
            ""
          )}
          <div className="items-center w-4/5">
            <p className="text-lg">{props.label}</p>
            <p className="font-extralight text-sm">{props.description}</p>
            <p className="font-light text-sm">{props.sub_description}</p>
          </div>
        </div>
      </div>
      {props.action ? (
        <div className="flex flex-col justify-center items-center">
          <a
            href="#"
            className="w-full rounded-full border border-gray-300 bg-white px-2.5 py-0.5 text-sm font-medium leading-5 text-gray-700 shadow-sm hover:bg-gray-50"
          >
            Action
          </a>
        </div>
      ) : (
        ""
      )}
    </div>
  );
};

export { Card };
