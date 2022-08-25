import { useState } from "react";
import { classNames } from "../../../utils/helpers";

interface ClassName {
  confirmable?: {
    confirm?: string;
    cancel?: string;
  };
}

interface ICardInterface {
  confirmable?: () => void;
  image?: JSX.Element;
  label: string;
  description?: string;
  sub_description?: string;
  action?: JSX.Element;
  id?: number;
  class?: ClassName;
  disable?: ActionAble;
}

interface ActionAble {
  confirmable: boolean;
}

const Card = (props: ICardInterface) => {
  const [disable, setDisable] = useState<ActionAble>({
    confirmable: false,
  });

  return (
    <div className="block relative" id="action-detail">
      {props.confirmable && !disable.confirmable ? (
        <div
          className={classNames(
            "w-full rounded-lg overflow-hidden cursor-pointer hidden"
          )}
          id={`action-confirm-${props.id}`}
        >
          <div
            className={classNames(
              props.class?.confirmable?.confirm,
              "w-1/2 flex items-center justify-center my-auto text-2xl font-semibold bg-red-600 h-full text-white"
            )}
            onClick={() => {
              document
                .querySelector(`#action-confirm-${props.id}`)
                ?.classList.add("hidden");
              document
                .querySelector(`#item-list-${props.id}`)
                ?.classList.remove("hidden");

              if (props.confirmable != undefined) {
                props.confirmable();
              }
            }}
          >
            Confirm!
          </div>
          <div
            className={classNames(
              props.class?.confirmable?.cancel,
              "w-1/2 flex items-center justify-center my-auto text-2xl font-semibold h-full bg-gray-200"
            )}
            id={`cancel-confirm`}
            onClick={() => {
              document
                .querySelector(`#action-confirm-${props.id}`)
                ?.classList.remove("flex");
              document
                .querySelector(`#action-confirm-${props.id}`)
                ?.classList.add("hidden");
              document
                .querySelector(`#item-list-${props.id}`)
                ?.classList.remove("hidden");
            }}
          >
            Cancel!
          </div>
        </div>
      ) : (
        ""
      )}
      <div
        className="w-full rounded-lg overflow-hidden cursor-pointer"
        id={`item-list-${props.id}`}
      >
        {props.image ? (
          <div className="bg-lakasir-primary rounded-lg w-[91px] h-[93px] absolute flex items-center justify-center overflow-hidden mr-7">
            {props.image}
          </div>
        ) : (
          ""
        )}
        <div className="flex items-center">
          {props.image ? <div className="w-1/2 h-[93px] mr-7"></div> : ""}
          <div className="items-center w-full">
            <p className="text-xl">{props.label}</p>
            <p className="font-extralight text-sm">{props.description}</p>
            <p className="font-light text-sm">{props.sub_description}</p>
          </div>
          <div
            className="w-1/2 h-full"
            onClick={() => {
              document
                .querySelector(`#action-confirm-${props.id}`)
                ?.classList.remove("hidden");
              document
                .querySelector(`#action-confirm-${props.id}`)
                ?.classList.add("flex");
              if (!props.disable?.confirmable) {
                document
                  .querySelector(`#item-list-${props.id}`)
                  ?.classList.add("hidden");
              }

              if (props.disable?.confirmable) {
                setDisable({ confirmable: true });
              }
            }}
          >
            {props.action ? props.action : ""}
          </div>
        </div>
      </div>
    </div>
  );
};

export default Card;
