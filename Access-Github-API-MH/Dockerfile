# in case we ever do any installs
# lets make them non-interactive
ARG DEBIAN_FRONTEND=noninteractive 

###### stage 1 - build image with dependencies

# use existing haskell image as our base
FROM fpco/stack-build:lts-16.23 as base-compile-image 
#fpco stack build is an image > v.big has all the stack, haskell compilers 
#using th elatest image = 16.23, update this as you go (Should there be a newer version)
# Set whatever we get back as base-compile-image this will be availbale to us

#Set a wd below
WORKDIR /opt/github-get
RUN stack update
# Gets the latest versions of stack/librarys 

# copy the yaml and cabal files
COPY ./github-get.cabal /opt/github-get
COPY ./stack.yaml /opt/github-get

# Docker will cache this command as a layer, savinf us the trouble or rebuilding
# dependencies unless we change files above.
RUN stack build --only-dependencies -j4
# If something has been changed in two of the copied files Docker will notice and know it has to keep going and execute the above code
# The j4 bit basically gives it permission to take over the machines processing power till its done, goes up to higher j's if want i.e. j8

##### stage 2 - compile the code

FROM base-compile-image as compile-image
## Sets Image we just built in stage 1 to new image that we are going to use ro do the deployment

## Below we are copying the local directory, if any change to the project this will get rerun
COPY . /opt/github-get
# do the build
RUN stack build --system-ghc
## Dont want stack to install a local sandboxed version of the ghc compiler here as on docker thats a change and have to keep doing this change
## Over and over again, already have a stack ghc compiler in place because of the image we have used so just indicating that we want
## to use whats avaialble  on the system > using long term service realese to decide what compiler we want to do the work


##### stage 3 - build small production image

## Going to use a bare unbuntu image and call it the run time image
FROM ubuntu:18.04 as runtime-image

## Purpose of below stuff is to make sure there are (Secure) certificates installed, to do with HTPS kind of stuff, that identify the endpoint as being authenticate
## Will ensure a base line ubuntu image that doesnt have all that stuff installed will end up with all that stuff installed
ARG DEBIAN_FRONTEND=noninteractive
RUN echo "building runtime-image" && \
    apt-get update && \
    apt-get install -y libssl1.0.0 && \
    apt-get install -y netbase && \
    apt-get install -y ca-certificates
  ## That stuff is Gonna do some installation above

## making a directory and then making the work directory
RUN mkdir -p /opt/github-get 
WORKDIR /opt/github-get
ENTRYPOINT ["/opt/github-get/github-get-exe"]
COPY --from=compile-image /opt/github-get/.stack-work/dist/x86_64-linux/Cabal-3.0.1.0/build/github-get-exe/github-get-exe .
CMD [""]
## All the Copy bit is copying from where your build is and the string being used is where the image gets built to. Can notice this in the output of the compiler 
## ...will be visible like this "Installing in library in <StringPathIWant>". Gets listed in the build, might not have it exactly wriet in previous comment sentence

## Copying the actual program into /opt/github-get which allows the entry point to be that actual program. Usually youw ould just use command
## Need to make an entrypoint and cmd (command) with nothing in it to be able to take in input
## What docker does is it uses the entrypoint as the start of the thing to be executed and it appends whatever you put int he command line to that, so getting
## command line parameters passed in for free

## When run this now wont be waiting for ages for it to build,
## Does this build in the virtual machine so not local