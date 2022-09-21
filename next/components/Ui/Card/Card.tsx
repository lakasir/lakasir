import { MouseEventHandler, useState } from "react";
import { Modal } from "../Modals";

interface IActionOption {
  label: string;
  icon?: JSX.Element;
  confirmable?: (confirm: boolean) => void;
  onClick?: () => void;
}

interface ICardInterface {
  onClick?: (e: MouseEventHandler<HTMLDivElement>) => void;
  image?: JSX.Element;
  label: string;
  description?: string;
  sub_description?: string;
  action?: IActionOption[];
  id?: number;
}

interface ShowActionInterface {
  stock: boolean;
}

const Card = (props: ICardInterface) => {
  const [show, setShow] = useState<ShowActionInterface>({
    stock: false,
  });
  return (
    <>
      <Modal onClose={(status) => setShow({ stock: status })} open={show.stock}>
        <div className="w-11/12 mx-auto bg-white pb-4 p-2 rounded-md">
          <p className="text-center text-lg py-2 w-full border-b-2 border-b-gray-300">
            Action
          </p>
          <div className="flex flex-col justify-center items-center gap-y-2">
            {props.action?.map((item, index) => (
              <li
                key={index}
                className="w-full cursor-pointer flex items-center justify-between bg-white px-2.5 py-0.5 text-sm font-medium leading-5 text-gray-700 shadow-sm hover:bg-gray-50"
                onClick={() => {
                  if (item.onClick) {
                    item.onClick();
                  } else if (item.confirmable) {
                    const confirm = window.confirm("would you sure this action?");
                    item.confirmable(confirm);
                  } else {
                    throw new Error("Action not found");
                  }
                  setShow({ stock: false });
                }}
              >
                <div>{item.label}</div>
                <div>{item.icon ? item.icon : ""}</div>
              </li>
            ))}
          </div>
        </div>
      </Modal>
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
            <button
              onClick={(e) => {
                e.preventDefault();
                setShow({ stock: !show.stock });
              }}
              className="w-full rounded-full border border-gray-300 bg-white px-2.5 py-0.5 text-sm font-medium leading-5 text-gray-700 shadow-sm hover:bg-gray-50"
            >
              Action
            </button>
          </div>
        ) : (
          ""
        )}
      </div>
    </>
  );
};

export { Card };
