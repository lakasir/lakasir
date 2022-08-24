import Image from "next/image";
import Link from "next/link";
import { useState } from "react";
import Button from "./Button";

interface IOptionAction {
  label: string;
  icon: JSX.Element | string;
  confirmable?: boolean;
  onClick: () => void;
}

interface IFloatingActionButtonInterface {
  options: IOptionAction[];
  title: string;
  action: string;
  dismissable?: boolean;
}

const FloatingActionButton = (props: IFloatingActionButtonInterface) => {
  const [show, setShow] = useState({
    option: false,
  });

  return (
    <div className="flex justify-between items-end fixed bottom-0 w-11/12">
      <div className="w-full">
        {show.option ? (
          <div id="action-option" className="space-y-6 mb-6 w-full">
            {props.options.map((el, index) => (
              <div className="flex justify-start items-center" key={index}>
                <div className="w-1/6">
                  <Button
                    className="w-14 rounded-full flex justify-center items-center drop-shadow-2xl h-14 bg-red-500 my-0"
                    onClick={() => {
                      if (!el.confirmable) {
                        el.onClick();
                        if (props.dismissable) {
                          setShow({ option: !show.option });
                        }
                      } else {
                        const labelEl = document.querySelector(
                          `#label-${index}`
                        );
                        labelEl?.classList.add("hidden");

                        const confirmEl = document.querySelector(
                          `#confirm-${index}`
                        );
                        confirmEl?.classList.remove("hidden");
                      }
                    }}
                  >
                    {el.icon}
                  </Button>
                </div>
                <p
                  className="ml-2 px-1 py-0.5 bg-black opacity-70 text-white rounded-sm text-lg"
                  id={`label-${index}`}
                >
                  {el.label}
                </p>
                {el.confirmable ? (
                  <div className="ml-6 w-full hidden" id={`confirm-${index}`}>
                    <div className="flex justify-between gap-x-3">
                      <Button
                        className="w-1/2 h-12 my-0 rounded-xl bg-red-600 drop-shadow-md"
                        onClick={() => {
                          alert("Confirmed!");
                          const confirmEl = document.querySelector(
                            `#confirm-${index}`
                          );
                          confirmEl?.classList.add("hidden");
                          const labelEl = document.querySelector(
                            `#label-${index}`
                          );
                          labelEl?.classList.remove("hidden");
                          el.onClick();
                          if (props.dismissable) {
                            setShow({ option: !show.option });
                          }
                        }}
                      >
                        Confirm!
                      </Button>
                      <Button
                        className="w-1/2 h-12 my-0 rounded-xl bg-gray-100 text-black drop-shadow-md"
                        onClick={() => {
                          alert("Canceled");
                          const confirmEl = document.querySelector(
                            `#confirm-${index}`
                          );
                          confirmEl?.classList.add("hidden");
                          const labelEl = document.querySelector(
                            `#label-${index}`
                          );
                          labelEl?.classList.remove("hidden");
                        }}
                      >
                        Cancel
                      </Button>
                    </div>
                  </div>
                ) : (
                  ""
                )}
              </div>
            ))}
          </div>
        ) : (
          ""
        )}
        <div>
          <Button
            className="w-14 py-4 rounded-xl flex justify-center items-center drop-shadow-2xl h-14"
            onClick={() => setShow({ option: !show.option })}
          >
            <Image
              src="/assets/icons/Single Choice.svg"
              width={30}
              height={30}
            />
          </Button>
        </div>
      </div>
      <Link href={props.action}>
        <Button className="w-4/5 py-4 rounded-xl drop-shadow-2xl text-lg font-semibold h-14 absolute right-0">
          {props.title}
        </Button>
      </Link>
    </div>
  );
};

export default FloatingActionButton;
