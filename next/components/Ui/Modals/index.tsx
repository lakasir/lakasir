interface IModalInterface {
  open: boolean;
  children: JSX.Element;
  onClose: (status: boolean) => void;
}

const Modal = (props: IModalInterface) => {
  if (!props.open) {
    return <></>;
  }

  return (
    <div className="z-50 top-0 left-0 fixed h-screen w-screen bg-black bg-opacity-70">
      <div
        className="flex justify-center items-center h-full"
        id="modal-bg"
        onClick={(e) => {
          if ((e.target as Element).matches("#modal-bg")) {
            props.onClose(false);
          }
        }}
      >
        {props.children}
      </div>
    </div>
  );
};

export { Modal };
