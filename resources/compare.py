import sys
from itertools import izip
import Image

file1, file2 = sys.argv[1:1+2]

i1 = Image.open(file1)
i2 = Image.open(file2)
assert i1.mode == i2.mode, "Different kinds of images."
assert i1.size == i2.size, "Different sizes."
 
pairs = izip(i1.getdata(), i2.getdata())
if len(i1.getbands()) == 1:
    dif = sum(abs(p1-p2) for p1,p2 in pairs)
else:
    dif = sum(abs(c1-c2) for p1,p2 in pairs for c1,c2 in zip(p1,p2))
 
ncomponents = i1.size[0] * i1.size[1] * 3
print (dif / 255.0 * 100) / ncomponents